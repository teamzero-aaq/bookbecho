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
define( 'DB_NAME', 'bookbecho' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '7|P1h@3r-bt%IJzI^J%!4b/uT]Km[/ Sg ~U3aT PQRi43gIO1UY?#S=qDgk~ANe' );
define( 'SECURE_AUTH_KEY',  'K(=t4M!r$arpulG?SPEU(NUz,J{5!tn~0E09@lmvgH#CC/j]p`H?Mfu`,+fG<g2w' );
define( 'LOGGED_IN_KEY',    '<;u}k6hr:}ah8*zfV0Mwc*$zY>{R<d/%MdFn8W%B_~kX<ukaKsQlcEIDBeCPP{{8' );
define( 'NONCE_KEY',        'C{pO,0l$%2dd2T`Vd3^rHE1IqX<^ixYf`jKQlox)QB=,>s<}ELXx$hcc?xlUHHkR' );
define( 'AUTH_SALT',        '?/tX$CUIzl_T1<`gr=aDee[Q4 dLx~A7? N0@@imT}suXN[sJa#~-SKD@WO[I.wu' );
define( 'SECURE_AUTH_SALT', '<kERmOg;+#DW%?Kf/&[l|BW3ClP@t{@ZX8dHxTZ.ni_f%x2qcVU5;0Vu} @5xUy&' );
define( 'LOGGED_IN_SALT',   'c~.6aVH0moIQS?.WE.u]g8[k;qSi7Do.sd[VD9kHgD3E(g,|oW:y&Ji4k$6#59>#' );
define( 'NONCE_SALT',       '8OCkA|syPBPRO4TsuIMreBv[t+ >f<`y&ole13O>)c;5rQGgf!lm8Gvo->c,|)Q6' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );