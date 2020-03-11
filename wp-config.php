<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'liljades_wp132' );

/** MySQL database username */
define( 'DB_USER', 'liljades_wp132' );

/** MySQL database password */
define( 'DB_PASSWORD', 'S)2v99H(ep' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'bfj6hxtuljq1x4qvlqcpol446fqin24qe4evnu8ow9g9aw6q9jlgpauzj1ujwprz' );
define( 'SECURE_AUTH_KEY',  'jayq6yzbakj2gqas9dgmd8hr6ebkoswc1gn8qbwulvutgmx2vmdnbs7owvhxqfhp' );
define( 'LOGGED_IN_KEY',    'chqdvlefp8tivms6p7bcpigybuko4ywafr9eezmzsoi6qb0xsqrtqvdn0lsd0iuj' );
define( 'NONCE_KEY',        'em2mvl7usceue3z4ulzfkj7ogvria8jnbrojuwhsi5jxcezpyg9r6q09dwln1aia' );
define( 'AUTH_SALT',        'r40blvrye23zni5rkfplaz0uguqdshb8lvt8q15tbf1b1gqcdfazibkxuymvnmp3' );
define( 'SECURE_AUTH_SALT', '3hae7bcpeaxad1be0cgrddlm78oq0fpxrqaptlb5vdvgidadfyhbi5jggeefxru1' );
define( 'LOGGED_IN_SALT',   'm30jafkrtxok7vey3dp2enevbztyiwjqa76polf2cxk4n9mqh79va057gbmqjo47' );
define( 'NONCE_SALT',       'zg5iuun9lfuw20ram8bm0vjkm78dpltddy7nzhnotc3qtuxzhu2gshfdeoxfz7kd' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wper_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Multisite */
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'studyabroadu.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

# Disables all core updates. Added by SiteGround Autoupdate:
define( 'WP_AUTO_UPDATE_CORE', false );

@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system

