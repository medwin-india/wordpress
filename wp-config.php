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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'medwin' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'cR(Su<;oTb102T|2g|x2G=`+mPo-mX7H?tD:_||gEV0Jx.51W71scEqdK5-|i%pI' );
define( 'SECURE_AUTH_KEY',  'u~`8[ &z]@o0*Y+bq:e{Y6meT;d[z5HkMBuQJk95rpON!K#Z@}Dgj}31za3}pyAe' );
define( 'LOGGED_IN_KEY',    'PUM=-~rOENNi(G}r7|m.2g*,p2BL+>gH]UP[Q0u8 dp0QF@!fzHWB]pYc=VlPU|O' );
define( 'NONCE_KEY',        'f};odHP:HeWx>34pGbeD09495ZOa>N[ZLLNO+~3A(v,7EKNtl-JM DxJT*~1@zh<' );
define( 'AUTH_SALT',        'a~+2U] Gsw0wpS9!|>zeR@H+J<+vDCJH0D?])e_(.E8<-z|w2#yQx>.a[ b`>Eul' );
define( 'SECURE_AUTH_SALT', '2[H:~x?zxBDL%A$<z8yvaCsLvP1[*RqtDGhS}=G&OSdR`b[>CthS,*+K ZX{5z?S' );
define( 'LOGGED_IN_SALT',   '?0;9r!Aac#e96,Kjy-o8qpUvn mqm+@]!D^X]T]t.Du=!NcHf.HPc|YYf6?:P]_L' );
define( 'NONCE_SALT',       '0pz4K~&wh!iUaJ~1!HALHuYZ-2W^1iT9,Mgmhh]lZ6~VCiI&<*$.|tVc`64-H!Wx' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'mw_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
