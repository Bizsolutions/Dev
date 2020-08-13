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
define( 'DB_NAME', 'topmovingreviews_testing-blog' );

/** MySQL database username */
define( 'DB_USER', 'topmovingreviews_testingblog' );

/** MySQL database password */
define( 'DB_PASSWORD', '_CTXElHYzYx@' );

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
define( 'AUTH_KEY',         'd]pm}VWDQu25u%UN<#>*OD91el4Lze})iXR$Cl!K .[oQ,3JAfgSx.m&x)eoWyvE' );
define( 'SECURE_AUTH_KEY',  'CmP#M]r)[NpI+=LihYQZvxC,f..EkR#4uJuaWSmoTMsjZ+CB.`0KJU^QNKD._;MC' );
define( 'LOGGED_IN_KEY',    '1LofFd&sQA>M9-dDmBV:h_U}2niX EQ`IM7>w~O#Q&6#[,3wBkQim{/[}W4SHQ?+' );
define( 'NONCE_KEY',        '(, ZDX%da{SzP9Yj0^i-u#o=8lD6zC=L!PZpBDwE<9uw9nyZ 4{eslXz__#wG vl' );
define( 'AUTH_SALT',        'm%w=KJ<!</2TL>o]r?KJ.:LyNL=-fAPs&F!LO}r?,jNK~%1rHe^ys>RMs%b(uK,[' );
define( 'SECURE_AUTH_SALT', '1BsYtMDMKw%h,L<$y+V!kh7$!vmr)XuM@#aWQ><f@#PJ/K$.T8=CGH8gQ%H@$<%n' );
define( 'LOGGED_IN_SALT',   '6dllsk^hH*zM.%uu7,|zq)LU5BtH,K{V;#p1[m7e3m1BPj5 yRna3l/p&8A?XgSz' );
define( 'NONCE_SALT',       '68$*B_FWGS}Tmm3q?licm^WD{k5;,sn>3GYlsV|2z`VIY?,r`oW0U93KxJM5Z;G+' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'TopMoving_';

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
