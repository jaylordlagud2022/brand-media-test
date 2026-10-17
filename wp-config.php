<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '=lD/*bSGhx}UvAzF}DF35?Jas,_z:F?x2=dCF>>?gO5?T_wXb..<^3Oyk@LT4*BV' );
define( 'SECURE_AUTH_KEY',   'TUpG4d*:f1QTs-9?QZ^%f)kI*0u@&3@|R,%!n&!9QvZvkIxM~klN )/>znif8K~G' );
define( 'LOGGED_IN_KEY',     's!Bsu]-p0l5ER|4%gRLJfzaiI@*JN|K!.P=qE0+m^X*3/TQT<)z-#ZWHDsh[w[UF' );
define( 'NONCE_KEY',         '(eFMFC,Ui:$9:fZd}#No3L-},~FUMF,d3j-JaI%8tk}X AXcO:3/A4GkQ/8~5f-w' );
define( 'AUTH_SALT',         'DoFGcV?&[vPkuZ,ulV(:Ws5|T^Z9kUstA~6*O:efOMMj-H6`uoab)=NEVt21yhOA' );
define( 'SECURE_AUTH_SALT',  'T3#]{)dUgc+0EClMa_ttsWDxixT_)C1?Yywm!_r1*U{a.* kjf4(6 ?p75g?jyBK' );
define( 'LOGGED_IN_SALT',    'y<r:G5D%Ev<IeS}T:P*h+9dJ5GrQ4i>LfeRPPZ2.OmPI`BT5YxXn3nim()A{LaS4' );
define( 'NONCE_SALT',        '$(GN80x%3|[ODU{Ef3}Cz70f}|B:xmO@YyQsj&!m>?S:df7wbz-2Tx8)36O~9(:c' );
define( 'WP_CACHE_KEY_SALT', '04FCIB#`4xTpLneMnM0?_~~:$S+]#ze?|muR=cyBbIt<1pw?y<f~jLie5ar:d^h)' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
