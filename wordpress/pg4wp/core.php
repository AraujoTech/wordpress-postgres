<?php
/**
 * @package PostgreSQL_For_Wordpress
 * @version $Id$
 * @author	Hawk__, www.hawkix.net
 */

/**
* This file does all the initialisation tasks
*/

// This is required by class-wpdb so we must load it first
require_once ABSPATH . '/wp-includes/version.php';
require_once ABSPATH . '/wp-includes/cache.php';
require_once ABSPATH . '/wp-includes/l10n.php';

if (!function_exists('wpsql_is_resource')) {
    function wpsql_is_resource($object)
    {
        return $object !== false && $object !== null;
    }
}

// Load the driver defined in 'db.php'
require_once(PG4WP_ROOT . '/driver_' . DB_DRIVER . '.php');

// This loads up the wpdb class applying appropriate changes to it
$replaces = array(
    'define( '	=> '// define( ',
    'class wpdb'	=> 'class wpdb2',
    'new wpdb'	=> 'new wpdb2',
    'mysql_'	=> 'wpsql_',
    'is_resource'	=> 'wpsql_is_resource',
    '<?php'		=> '',
    '?>'		=> '',
);

// Ensure class uses the replaced mysql_ functions rather than mysqli_
if (!defined('WP_USE_EXT_MYSQL')) {
    define('WP_USE_EXT_MYSQL', true);
}
if (WP_USE_EXT_MYSQL != true) {
    throw new \Exception("PG4SQL CANNOT BE ENABLED WITH MYSQLI, REMOVE ANY WP_USE_EXT_MYSQL configuration");
}

eval(str_replace(array_keys($replaces), array_values($replaces), file_get_contents(ABSPATH . '/wp-includes/class-wpdb.php')));

// Create wpdb object if not already done
if (!isset($wpdb) && defined('DB_USER')) {
    $wpdb = new wpdb2(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
}
