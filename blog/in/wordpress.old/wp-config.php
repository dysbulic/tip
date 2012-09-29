<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'hoenir_wordpress');

/** MySQL database username */
define('DB_USER', 'wjholcomb');

/** MySQL database password */
define('DB_PASSWORD', 'FuckOff!');

/** MySQL hostname */
define('DB_HOST', 'mysql.himinbi.org');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Hi3P5llT(I7yiG`hbv8cryvWmrm/Ar^~xY0;fpw5nJ#d7@@43dQh5ng;E:^t/K66');
define('SECURE_AUTH_KEY',  'MOl&"#u&E5ciXn6a65P8vV8E$JK`oFpKTtus_ixRdS^wlpU/r%klI$G:aUh)3Da~');
define('LOGGED_IN_KEY',    'm)gYNTus)uy:s|qBl777(1VQtZGOl?`*ZzpaYCZv)2&o5!gZ%cIOoSb8Gxw10pW"');
define('NONCE_KEY',        'YQJAm$jtEaZuwG@dZqk(E^dY^63eL`O&F4K_U51`w@7idYQJYE*/:0dw$r_Rxv67');
define('AUTH_SALT',        '2&J9bWi+TSP"oj4WKe~nFrL:#J&#zAm~a3z9r%DgD"7M$vi4g;NCWPG`(G72*Wz$');
define('SECURE_AUTH_SALT', '0zsVk@GH_jC;ug~1DjpuGj&(k:prdV5z$`QS`G?XpPModv!VI^%h;xLi2qFq8sbz');
define('LOGGED_IN_SALT',   '@(N)wvKf&$#VL$$0*|:UzML?M|wI6~)XOe9OF(?$O#Kgqo3?1|E4H!8vzj~UX4)9');
define('NONCE_SALT',       'o/LXUh1(AKoJ7W0E~(*&ZY;O:*Ep?z`&ML)"9hdaOn"RTcxjD&&0cBmzH(Fke4Wl');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_6dzxa7_';
//$table_prefix  = 'wp_u7vtwo_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

