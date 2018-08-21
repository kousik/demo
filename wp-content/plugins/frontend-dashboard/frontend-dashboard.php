<?php
/**
 * Plugin Name: SpecsMind Frontend Dashboard
 * Description: Front end dashboard provide high flexible way to customize the user dashboard on front end rather than WordPress wp-admin dashboard.
 * Version: 5.2.13
 * Author: Kousik
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Version Number
 */
define( 'BC_FED_PLUGIN_VERSION', '5.2.13' );
define( 'BC_FED_PLUGIN_VERSION_TYPE', 'FREE' );

/**
 * App Name
 */
define( 'BC_FED_APP_NAME', 'Frontend Dashboard' );

/**
 * Root Path
 */
define( 'BC_FED_PLUGIN', __FILE__ );
/**
 * Plugin Base Name
 */
define( 'BC_FED_PLUGIN_BASENAME', plugin_basename( BC_FED_PLUGIN ) );
/**
 * Plugin Name
 */
define( 'BC_FED_PLUGIN_NAME', trim( dirname( BC_FED_PLUGIN_BASENAME ), '/' ) );
/**
 * Plugin Directory
 */
define( 'BC_FED_PLUGIN_DIR', untrailingslashit( dirname( BC_FED_PLUGIN ) ) );

/**
 * User Profile Table Name
 */
define( 'BC_FED_USER_PROFILE_DB', 'fed_user_profile' );
/**
 * Dashboard Menu Items
 */
define( 'BC_FED_MENU_DB', 'fed_menu' );
/**
 * Post Fields
 */
define( 'BC_FED_POST_DB', 'fed_post' );
/**
 * Plugin URL
 */
define( 'BC_FED_API_PLUGIN_LIST', 'https://buffercode/api/v1/fed/plugin_list' );




require_once BC_FED_PLUGIN_DIR.'/functions/data.php';
require_once BC_FED_PLUGIN_DIR.'/functions/extensions/uri/functions.php';
require_once BC_FED_PLUGIN_DIR.'/templates/functions.php';
require_once BC_FED_PLUGIN_DIR.'/include/display.php';

require_once BC_FED_PLUGIN_DIR . '/fed_autoload.php';
require_once BC_FED_PLUGIN_DIR . '/include/functions.php';
require_once BC_FED_PLUGIN_DIR . '/include/mail.php';
require_once BC_FED_PLUGIN_DIR . '/include/ajax-functions.php';
require_once BC_FED_PLUGIN_DIR . '/include/theme-functions.php';
require_once BC_FED_PLUGIN_DIR . '/include/api.php';
require_once BC_FED_PLUGIN_DIR . '/include/api-functions.php';

register_activation_hook( __FILE__, 'isms_add_custom_roles_to_all_sites' );
register_deactivation_hook( __FILE__, 'isms_remove_custom_roles_from_all_sites' );

/*tables that need to be created for each site*/
add_action( "init", "tls_database_table_creation", 1 );

/**
 * Create table
 */
function tls_database_table_creation(){
    global $wpdb;

    /*create database tables if they don't exist*/
    require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );

    if ( !empty($wpdb->charset) )
        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

    $sql[] = "CREATE TABLE IF NOT EXISTS `wp_cust_notify` (
		`id` bigint(20) NOT NULL auto_increment,
		`sender_user_id` int(20) NOT NULL,
		`message` varchar(200) NOT NULL,
		`recp_user_id` int(20) NOT NULL,
		`is_read` int(1) NOT NULL,
		`accept` int(1) NOT NULL,
		`date` datetime default NULL,
		PRIMARY KEY  (`id`)
		)  {$charset_collate};";

	$sql[] = "CREATE TABLE IF NOT EXISTS `wp_cust_loc` (
		`id` bigint(20) NOT NULL auto_increment,
		`user_id` int(20) NOT NULL,
		`lat` varchar(100) NOT NULL,
		`long` varchar(100) NOT NULL,
		`date` datetime default NULL,
		PRIMARY KEY  (`id`)
		)  {$charset_collate};";
		
	$sql[] = "CREATE TABLE IF NOT EXISTS `wp_cust_contacts` (
		`id` bigint(20) NOT NULL auto_increment,
		`user_id` int(20) NOT NULL,
		`contacts` longtext NOT NULL,
		`date` datetime default NULL,
		PRIMARY KEY  (`id`)
		)  {$charset_collate};";	


    foreach( $sql as $table )
        dbDelta( $table );
}
