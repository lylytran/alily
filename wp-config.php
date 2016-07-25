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
define('DB_NAME', 'alily');

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
define('AUTH_KEY',         ',a]bTO|HKp)bw$tYC,3&5zRGPQ#~4<*|+XIzJN9k2c:yni`h5{r]=3tj9|HxYVjm');
define('SECURE_AUTH_KEY',  'R5V]nc;|Lw$v|[MV W%p]0+G*GMYuO7#KvN-V#rx;#%!iuM&xZ%DGORalelT@y5$');
define('LOGGED_IN_KEY',    'b1DAs+j8Nq$}[.Xt@8Z0DEc{K|Qc/x8K.u00QsgdL~LbZ0:D]T1-+,9F+n&j+JWu');
define('NONCE_KEY',        'TRZw3pMpu;(/#|?bC?P Fq:n$D)P:!~3wJ[ @3cAHEETU_G+FH?d.s@!@BkPRMOC');
define('AUTH_SALT',        'J6zs3^Kvl!5]PdVadm@piRE/4}KHQ#,N|Lv1`<5*?Kl?ht.l+;ds3*ZCOY67MF#q');
define('SECURE_AUTH_SALT', '#u7UZ|wSXM%@M-k4)Ze*~*`Hux1MRRD>K@~4kj(]D-#N*T-v5d;)[6.-f=Ji T_<');
define('LOGGED_IN_SALT',   'd%okr<q5n`Hd@OWIF+X&LdzE%|R#[%=thngC(W[+kA@=PoMV6If/Gmfm_p)BNxXh');
define('NONCE_SALT',       'D47Z>>`bg.+58R@9--2<lnVc7y )7mBh|d<Qg9?.j@a~yOk/S4J&z[g-S.4%aBz^');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
