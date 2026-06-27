<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'wpuser' );

/** Database password */
define( 'DB_PASSWORD', 'Wp_Strong_Pass_2026!' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^7~]&&PxYff/Ycm(IX^&L{i7{j%UyL8DM+z[3Bf{KUvwq^oeB[|J~+^NC)bZn&=z');
define('SECURE_AUTH_KEY',  'HA;-ZSF!PA-b6n*7}Ftx)H+b,@dZTntN}S]a@5ay]vsw)k2nKi45];W7a!tJ|$&/');
define('LOGGED_IN_KEY',    '@bpi!aABlP;A&xRlGh]|yn!EI1hBWr05yLYs[9j1#T~w[Yv-RVA]6@JIoRsZ+0->');
define('NONCE_KEY',        'Y8m<@H0Bc<Q7pAUD&ZP7>?J[UA7rfNjj$KYw;mm?G$KO5yvx$|ge GN^]%!--aYx');
define('AUTH_SALT',        'zGmeUaKSaMYm=&*?Oc~ V-AV@yFw;&-J<fN>xf-vPz9+s%)g+g2]FLxdP*+{ujV&');
define('SECURE_AUTH_SALT', '.O3t@t1sJW0F_.ZYRvDyQZm+PPhP`T7ZeFk-}Igr;JNY,SDcX%ejM@e%EPaeOEnS');
define('LOGGED_IN_SALT',   '!zN&d-21PC/uG6X^ItV{2EmUUl(.u^bY#+V^g|<Agq2G0o/2@-~K3!7A6R7BdfTK');
define('NONCE_SALT',       'ie;,Xz1/&6%6fKBkXE6*l,J61r1,3E{W]+M+=pR5y(LSS@d~#xR[5IZ@SR$!X_uu');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* Security: Disable built-in file editor */
define('DISALLOW_FILE_EDIT', true);
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
