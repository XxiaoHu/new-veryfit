<?php
/**
 * GitHub Webhook Auto-Deploy for WordPress wp-content
 *
 * Configure GitHub to POST application/json payloads to this endpoint.
 * Set the shared secret in the WEBHOOK_DEPLOY_SECRET environment variable.
 */

// ===== CONFIGURATION =====
$secret = getenv('WEBHOOK_DEPLOY_SECRET');
$repo_dir = '/var/www/html/wp-content';
$branch = 'refs/heads/main';
$log_file = '/var/log/webhook-deploy.log';
$max_payload_bytes = 1024 * 1024;

// ===== LOGGING =====
function log_msg($msg) {
    global $log_file;
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
    if (@file_put_contents($log_file, $line, FILE_APPEND | LOCK_EX) === false) {
        error_log(trim($line));
    }
}

// ===== Only accept POST =====
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

if (empty($secret)) {
    http_response_code(500);
    log_msg('FAILED: WEBHOOK_DEPLOY_SECRET is not configured');
    die('Server misconfigured');
}

if (!isset($_SERVER['CONTENT_TYPE']) || stripos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {
    http_response_code(415);
    die('Unsupported Media Type');
}

if (isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $max_payload_bytes) {
    http_response_code(413);
    die('Payload Too Large');
}

// ===== Get payload =====
$payload = file_get_contents('php://input');
if ($payload === false || $payload === '') {
    http_response_code(400);
    die('Bad Request');
}

// ===== Verify GitHub signature =====
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    log_msg('FAILED: Invalid signature from ' . $_SERVER['REMOTE_ADDR']);
    die('Forbidden: Invalid signature');
}

// ===== Parse event =====
$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';
$data = json_decode($payload, true);
if (!is_array($data)) {
    http_response_code(400);
    log_msg('FAILED: Invalid JSON payload from ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    die('Bad Request');
}

if ($event !== 'push') {
    // Not a push event, ignore but respond OK (GitHub sends ping events)
    log_msg("IGNORED: Event type '$event' (not a push)");
    header('Content-Type: text/plain');
    die("OK - event '$event' ignored");
}

// ===== Check branch =====
$ref = $data['ref'] ?? '';
if ($ref !== $branch) {
    log_msg("IGNORED: Push to '$ref' (not $branch)");
    header('Content-Type: text/plain');
    die("OK - branch '$ref' ignored");
}

// ===== Execute git pull =====
log_msg("PULL: Starting git pull on branch main...");

$cmd = "git -C " . escapeshellarg($repo_dir) . " pull --ff-only origin main 2>&1";
$output = [];
$return_code = 0;
exec($cmd, $output, $return_code);

$result = implode("\n", $output);
log_msg("PULL result (code $return_code): $result");

// ===== Fix permissions after pull =====
exec("chown -R www-data:www-data " . escapeshellarg($repo_dir));

// ===== Clear WordPress cache if WP-CLI is available =====
if (file_exists('/usr/local/bin/wp') || file_exists('/usr/bin/wp')) {
    exec('wp cache flush --path=/var/www/html 2>&1', $flush_out);
    log_msg("Cache flush: " . implode("\n", $flush_out));
}

// ===== Respond =====
http_response_code($return_code === 0 ? 200 : 500);
header('Content-Type: text/plain');
echo $return_code === 0 ? "OK - Deploy successful\n" : "ERROR - Deploy failed\n";
