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

// ** Database settings - Railway MySQL ** //
// Use environment variables if available, otherwise fall back to defaults
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('WORDPRESS_DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'railway' );

/** Database username */
define( 'DB_USER', getenv('WORDPRESS_DB_USER') ?: getenv('MYSQLUSER') ?: 'root' );

/** Database password */
define( 'DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') ?: getenv('MYSQL_ROOT_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: 'EAEAuwZrDfldCsVlILORIlRNrUqjTIPT' );

/** Database hostname - Railway internal network */
// Extract host and port from MYSQL_PRIVATE_URL if available
$db_host = 'mysql.railway.internal:3306';
if (getenv('MYSQL_PRIVATE_URL')) {
    $url = parse_url(getenv('MYSQL_PRIVATE_URL'));
    if ($url && isset($url['host'])) {
        $port = isset($url['port']) ? $url['port'] : 3306;
        $db_host = $url['host'] . ':' . $port;
    }
} elseif (getenv('WORDPRESS_DB_HOST')) {
    $db_host = getenv('WORDPRESS_DB_HOST');
} elseif (getenv('MYSQLHOST')) {
    $port = getenv('MYSQLPORT') ?: 3306;
    $db_host = getenv('MYSQLHOST') . ':' . $port;
}
define( 'DB_HOST', $db_host );

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
define( 'AUTH_KEY',          'tYjDc/5V$>7U8*uH@9lr4?0{fh&^0./vE@ LOa.7IE3]G/$|w]*L{]L*mNuCH%]k' );
define( 'SECURE_AUTH_KEY',   'Ukf ZUn5,EFeLoh@@#Rt$n5Cm-~jS?6FN)wFWD(*+,)}U8 >Qn[|C1]vjc_zQ8`K' );
define( 'LOGGED_IN_KEY',     'V/M~+D <DPY{1p*.@X<_m];/@|F*x.)7*y{GtIE6gY3^k!JO=q{[Ix qN:>(9B/&' );
define( 'NONCE_KEY',         '@%>k#Cp&]}hsvD]rwrFdKg(f8_5C$qYjg65rWp$mqgr1lgi]E;%!i}E1&!P`9N1`' );
define( 'AUTH_SALT',         'GGNKXp-a_h[M/!<5/bK.T8a<iT*S{01R<m#T{q@}IPgB#qe4hl_l$B[{cNbdY7`h' );
define( 'SECURE_AUTH_SALT',  '[/6-_oX~$@%Wp*&|)pl9V:R8mum]QT Tm=6k~bP+G;i)*!hADxlB?=Mh<bpMo%%y' );
define( 'LOGGED_IN_SALT',    'x8{M(t_+=wCY#*Al- H@lt 5)FFM3-zg=54B%jJagv~^g)SZ4xPT&VW{:h+/!xKW' );
define( 'NONCE_SALT',        'V!~}WJm8ux@g OzYDq78-ML#:3 !^]aLo/o7R<NVD4yRz&v[NQXJU><:5JdHk$8i' );
define( 'WP_CACHE_KEY_SALT', 'Fc<OqgTO#+MY.gpxgN(Zs%ma#MyW*wB3_*<7NRh9!2wHx(xT}86r*A:mecIp:lp;' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bk2v_';


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

define( 'WP_MEMORY_LIMIT', '512M' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
