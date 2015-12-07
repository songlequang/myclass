<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'itclass_29_9');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '.:m:p;.*6EuZ-FN !k~Qv~&O{@Sm9.iIL|J={J.I5782cTSl2oS78j;v*m-[>@A;');
define('SECURE_AUTH_KEY',  '(zID{ms$vCj6Do.Lz.dj1f]1N:]Y.Nm~S0<$Z$/R|$~1ba|{] >Mq-]PQKiXMq? ');
define('LOGGED_IN_KEY',    'QkQDaDI@+A2>DUGw;(1e9p{.[?=SKdDSJ=R[k/G&W]UB(;,&`5{M)R~{=wW-UH$`');
define('NONCE_KEY',        '>uAG*anc@c&(@k]-tzk)khkAC]!d>,RRYj>TK]Y;]jYl7j;RDU;Qo<=%C~+]x2-~');
define('AUTH_SALT',        'a{L#C(TIHbeEouVa++d+Gq*wL97f>(y9mYS5 QW.D.2P;DrUP0)RFnz~#eA^CCI,');
define('SECURE_AUTH_SALT', 'QmE[[>%{[3;9Dyu*N^hNAuyiz.|[lH4MOwI)d3t`->FqP!Y^LT{X[r6,9h/FdPjb');
define('LOGGED_IN_SALT',   '],-oe2)q+;fW-}jt)<k+v#Ji1sWR|VU-m^*D<E8F-%2]voZ`4F5]}G>g[TfE,+a$');
define('NONCE_SALT',       '0O~UT=U}-jR|$O(><&JgL^Aa?F-E8Zd{a674eb57,EH-,P0#7N*6IN;7=?0AN3)r');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'itclass_songle_';
define('WPLANG', 'vi');
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
