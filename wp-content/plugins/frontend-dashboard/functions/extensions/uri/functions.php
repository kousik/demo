<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*******************************************************************/

// Actions
add_action( 'init', 'epic_set_uri_globals', 1);
add_action( 'wp', 'epic_community_load_template_catch_uri', 3 );


/*
********************************
FUNCTIONS
********************************
*/

/**
* Returns the requested URI slot as a sanitized value
* @param int $index The slot number to retrieve
* @return string The sanitized URI slot
*/
function epic_uri_slot( $index = 0 ){
	global $epic;
    $slot = isset($epic->uri[$index])?$epic->uri[$index]:"";
    $sanitized_piece = epic_data_escape($slot, "strip");
	return $sanitized_piece;
}

/**
* @return bool|Array The template name and path. If URI does not match then returns false
*/
function epic_community_is_specified_template(){
	
	global $epic;


    /*if(epic_uri_slot() == 'roofing-request' && epic_uri_slot(1) == '')
        return array('template'=>'roofing-request', 'path' => '/');
    else else if(epic_uri_slot() == 'contractor-request' && epic_uri_slot(1) == '')
        return array('template'=>'contractor-request', 'path' => '/');*/
    if(epic_uri_slot() == 'sponsor' && epic_uri_slot(1) == 'sign-up')
        return array('template'=>'sponsor-sign-up', 'path' => '/');
    else if(epic_uri_slot() == 'roofing-request' && epic_uri_slot(1) == 'success')
        return array('template'=>'success', 'path' => '/');
    else if(epic_uri_slot() == 'contractor-request' && epic_uri_slot(1) == 'success')
        return array('template'=>'success', 'path' => '/');
    else if(epic_uri_slot() == 'dashboard' && epic_uri_slot(1) == 'edit-ad' && epic_uri_slot(2))
        return array('template'=>'ad-edit', 'path' => '/dashboard/');
    else if(epic_uri_slot() == 'sponsor' && epic_uri_slot(1) == 'payment' && epic_uri_slot(2))
        return array('template'=>'sponsor-ad-payment', 'path' => '/');
    else if(epic_uri_slot() == 'sponsor-payment' && epic_uri_slot(1) == 'success' && !epic_uri_slot(2))
        return array('template'=>'payment-success', 'path' => '/');
    else if(epic_uri_slot() == 'sponsor' && epic_uri_slot(1) == 'add' && epic_uri_slot(2) == 'new')
        return array('template'=>'ad-new', 'path' => '/dashboard/');
    else
        return false;
}

/**
* Loads the first template file found based on epic global.
* @uses epic_community_is_specified_template() (FROM THE 8020 COMMUNITY FUNCTIONS )
* @uses epic_community_template() (FROM THE 8020 COMMUNITY TEMPLATES FUNCTIONS )
* @return bool False if template file not found else loads the proper template
*/
function epic_community_load_template_catch_uri() {
	if( is_admin() )
		return false;

	global $wpdb;
	global $wp_query;
	global $epic;

	/* Don't hijack any URLs on blog pages */
	if ( !( $atts = epic_community_is_specified_template()) ) {
		$epic->autoload = false;
		return false;
	}
	
	/*ensure a 404 is not delivered*/
	$wp_query->is_404 = false;
	status_header( 200 );
	
	/*Loads the proper template*/
	$epic->autoload = true;
	$template = epic_community_template($atts['template'], $atts['path']);
	load_template( $template );
	die;
}


/**
* Set the URI slot globals
*/
function epic_set_uri_globals() {
	global $epic, $current_blog, $wpdb, $blog_id;

	//$current_blog = get_blog_details($blog_id);
    if(is_multisite()){
        $current_blog = get_blog_option($blog_id,'siteurl');
    }else{
        $current_blog = get_option('siteurl');
    }

	$epic = new stdClass;
	$epic->uri = array();
	$url = curPageURL();
	//$path = str_replace(site_url(), "", $url);
	$path = esc_url( $_SERVER['REQUEST_URI'] );

	if( strpos($path, 'wp-admin') )
		return false;

	// Firstly, take GET variables off the URL to avoid problems,
	// they are still registered in the global $_GET variable */
	$noget = substr( $path, 0, strpos( $path, '?' ) );
	if ( $noget != '' ) $path = $noget;

	/* Fetch the current URI and explode each part separated by '/' into an array */
	$et_uri = explode( "/", $path );
	/* Loop and remove empties */
	foreach ( (array)$et_uri as $key => $uri_chunk )
		if ( empty( $et_uri[$key] ) ) unset( $et_uri[$key] );

	if ( is_multisite() && $current_blog->blog_id != 1 ) {
		if ( $current_blog->path != '/' )
			array_shift( $et_uri );
	}

	/* Reset the keys by merging with an empty array */
	$et_uri = array_merge( array(), $et_uri );

	/* Set the current component */
	if(isset($et_uri[0]) && $et_uri[0] )
		$epic->uri[0] = $et_uri[0];

	/* Set the current action */
	if( isset($et_uri[1]) && $et_uri[1] )
		$epic->uri[1] = $et_uri[1];
	
	if( count($et_uri) > 2 ):
		$action_count = 2;
		for( $x = 2; $x < count($et_uri); $x++){
			$epic->uri[$action_count] = $et_uri[$x];
			$action_count++;
		}
	endif;

	if(!$epic->uri):
		$epic->uri = array();
	endif;
}

function curPageURL() {
	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
}
?>