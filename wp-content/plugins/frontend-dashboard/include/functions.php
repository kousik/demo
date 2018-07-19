<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 1/5/18
 * Time: 4:47 PM
 */

// Add custom roles to a specific blog
function isms_add_custom_roles_to_all_sites(  ) {
    $blog_id = 1;
    if ( $blog_id ) {

        $admin_prexisting = get_role( 'administrator' );


        $admin_prexisting->add_cap( 'distributor_view' );
        $admin_prexisting->add_cap( 'agent_view' );

        $distributor = add_role(
            'distributor',
            __( 'Distributor' ),
            array(
                'read'                 => true,
                'upload_media'         => true,
                'upload_files'         => true,
                'distributor_view'     => true,
            )
        );

        $agent = add_role(
            'agent',
            __( 'Agent' ),
            array(
                'read'                 => true,
                'upload_media'         => true,
                'upload_files'         => true,
                'agent_view'           => true,
            )
        );


        $customer = add_role(
            'customer',
            __( 'Customer' ),
            array(
                'read'                 => true,
                'upload_media'         => true,
                'upload_files'         => true
            )
        );






        if ( $distributor === null ) {
            error_log( 'Account Admin nternal Irole not created for blog ' . $blog_id );
        }

        if ( $agent === null ) {
            error_log( 'Account Admin customer role not created for blog ' . $blog_id );
        }

        if ( $customer === null ) {
            error_log( 'Read write customer role not created for blog ' . $blog_id );
        }
    }
}

// Remove custom roles from a blog
function isms_remove_custom_roles_from_all_sites() {
    remove_role( 'distributor' );
    remove_role( 'agent' );
    remove_role( 'customer' );
}



function fdb_get_state($country_code = 101){
    global $wpdb;
    //SELECT * FROM `wp_states` WHERE `country_id` = 231
    $states = $wpdb->get_results("SELECT * FROM `wp_states` WHERE `country_id` = {$country_code}");
    return $states;
}


function fdb_get_city($state_name = false){
    global $wpdb;
    global $wpdb;
    $state_id = $total = $wpdb->get_var("SELECT id FROM `wp_states` WHERE `country_id` = 101 AND `name` = '{$state_name}'");
    $cities = $wpdb->get_results("SELECT * FROM `wp_cities` WHERE `state_id` = {$state_id}");
    return $cities;
}

add_action('fed_get_city_data_processing', 'fed_get_city_data', 20);

function fed_get_city_data(){
    global $wpdb;
    $state_id = $total = $wpdb->get_var("SELECT id FROM `wp_states` WHERE `country_id` = 101 AND `name` = '{$_POST['state_id']}'");
    //SELECT * FROM `wp_states` WHERE `country_id` = 231
    $cities = $wpdb->get_results("SELECT * FROM `wp_cities` WHERE `state_id` = {$state_id}");
    ob_start();
    echo '<option value=""></option>';
    if($cities):
        foreach ($cities as $ckey => $cobj):
            ?>
            <option value="<?=$cobj->name?>"><?=$cobj->name?></option>
            <?php
        endforeach;
    else:
        echo '<option value="Not applicable">Not applicable..</option>';
    endif;
    $message = ob_get_contents();
    ob_end_clean();
    echo $message; die;
}


add_filter('pods_form_ui_field_pick_data_us_states', 'pod_get_city', 1, 10);

function pod_get_city($data){
    $states = fdb_get_state(101);
    $data = [];
    foreach ($states as $key => $s):
        $data[$s->name] = $s->name;
    endforeach;
    return $data;
}



function fixFilesArray(&$files)
{
    $names = array( 'name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);

    foreach ($files as $key => $part) {
        // only deal with valid keys and multiple files
        $key = (string) $key;
        if (isset($names[$key]) && is_array($part)) {
            foreach ($part as $position => $value) {
                $files[$position][$key] = $value;
            }
            // remove old key reference
            unset($files[$key]);
        }
    }
}


/**
 * From Frontend Dashboard version 1.2
 */
add_filter('fed_menu_default_page', 'fed_menu_default_page_custom', 3, 10);
//add_action( 'fed_frontend_dashboard_menu_container', 'fed_frontend_dashboard_menu_container_fn', 10, 2  );
add_action( 'fed_override_default_page', 'fed_frontend_dashboard_menu_container_fn', 10, 2  );

function fed_menu_default_page_custom($rbval, $menus, $index){


    if($index == 'settings'){
        return false;
    }

    if($index == 'agents'){
        return false;
    }

    if($index == 'distributors'){
        return false;
    }

    if($index == 'customers'){
        return false;
    }

    if($index == 'account_info'){
        return false;
    }
    
    if($index == 'account_info_dist'){
        return false;
    }

    return $rbval;
}

/**
 * Menu Container
 *
 * @param $request
 * @param $menu_items
 */
function fed_frontend_dashboard_menu_container_fn( $menu_items, $index) {


    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'settings' ) {
        $template = epic_community_template('settings', '/dashboard/');
        load_template( $template );
    }

    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'agents' ) {
        $template = epic_community_template('tpl-agents', '/dashboard/');
        load_template( $template );
    }

    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'distributors' ) {
        $template = epic_community_template('tpl-distributors', '/dashboard/');
        load_template( $template );
    }


    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'customers' ) {
        $template = epic_community_template('tpl-customers', '/dashboard/');
        load_template( $template );
    }

    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'account_info' ) {
        $template = epic_community_template('tpl-ag-account-info', '/dashboard/');
        load_template( $template );
    }
    
    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'account_info_dist' ) {
        $template = epic_community_template('tpl-dt-account-info', '/dashboard/');
        load_template( $template );
    }

}



function importImage( $ifile ) {
    // Include image.php
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Add Featured Image to Post
    $upload_dir = wp_upload_dir(); // Set upload folder
    $filename   = $ifile['name']; // Create image file name


    // Check folder permission and define file location
    if ( wp_mkdir_p( $upload_dir['path'] ) ) {
        $filename = wp_unique_filename( $upload_dir['path'], $filename );
        $file     = $upload_dir['path'] . '/' . $filename;
    } else {
        $filename = wp_unique_filename( $upload_dir['basedir'], $filename );
        $file     = $upload_dir['basedir'] . '/' . $filename;
    }

    if (move_uploaded_file($ifile["tmp_name"], $file)) {

        // Check image file type
        $wp_filetype = wp_check_filetype( $filename, null );

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // Create the attachment
        $attach_id = wp_insert_attachment( $attachment, $file );
        $taxonomy_image_url = wp_get_attachment_image_src($attach_id, 'full');
        $taxonomy_image_url = $taxonomy_image_url[0];

        $imagenew = get_post( $attach_id );
        $fullsizepath = get_attached_file( $imagenew->ID );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        return $attach_id;
    }


    return false;

}


//Contractor

add_action('fed_get_contractor_city_data_processing', 'fed_get_contractor_city_data', 20);

function fed_get_contractor_city_data(){
    global $wpdb;

    $exist = $wpdb->get_var("SELECT id FROM `wp_pods_contractor` WHERE `state` = '{$_POST['state_id']}'");
    if($exist):
        echo "not available";die;
    endif;

    $state_id = $total = $wpdb->get_var("SELECT id FROM `wp_states` WHERE `country_id` = 231 AND `name` = '{$_POST['state_id']}'");
    //SELECT * FROM `wp_states` WHERE `country_id` = 231
    $cities = $wpdb->get_results("SELECT * FROM `wp_cities` WHERE `state_id` = {$state_id}");
    ob_start();
    echo '<option value=""></option>';
    if($cities):
        foreach ($cities as $ckey => $cobj):
            ?>
            <option value="<?=$cobj->name?>"><?=$cobj->name?></option>
        <?php
        endforeach;
    else:
        echo '<option value="Not applicable">Not applicable..</option>';
    endif;
    $message = ob_get_contents();
    ob_end_clean();
    echo $message; die;
}


add_action('fed_check_valid_email_processing', 'fed_check_valid_email_processing', 20);

function fed_check_valid_email_processing(){
    global $wpdb;
    $data = [];
    if ( !is_email( $_POST['email'] ) ) :
        $data['error'] = 'Invalid email address!';
        echo json_encode($data);die;
    endif;

    $user_data = get_user_by('email', $_POST['email']);
    if ($user_data) :
        $data['error'] = 'Email address already taken!';
        echo json_encode($data);die;
    endif;

    $exist = $wpdb->get_var("SELECT id FROM `wp_pods_contractor` WHERE `email_address` = '{$_POST['email']}'");
    if($exist):
        $data['error'] = 'Email address already taken!';
        echo json_encode($data);die;
    endif;
    $data['error'] =false;
    echo json_encode($data);die;

}





function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }

    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function no_image_url(){
    return plugins_url('/_inc/images/noimage.png', BC_FED_PLUGIN );
}

/**
 * GEnerate random number
 * @param Integer $len
 * @return integer
 */
function isms_gen_id($len = 12,$name_cast = 'AG', $state = false){
    global $blog_id;
    $random = substr(number_format(time() * mt_rand(),0,'',''),0,$len);
    $com_id = "{$name_cast}-{$state}-{$random}";
    return $com_id;
}

function isms_gen_cust_id($len = 12,$name_cast = 'ISMS'){
    global $blog_id;
    $random = substr(number_format(time() * mt_rand(),0,'',''),0,$len);
    $com_id = "{$name_cast}{$random}";
    return $com_id;
}


function isms_get_users_by_role($role = "Agent"){
    $user_query = new WP_User_Query( array( 'role' => $role ) );
    if ( ! empty( $user_query->get_results() ) ):
        return $user_query->get_results();
    else:
        return false;
    endif;
}


function isms_get_total_agents_by_distributor($id){
    $u = get_user_by('ID', $id);
    $user_query = new WP_User_Query(
            array(
                'role' => 'Agent' ,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'dist_id',
                        'value'   => $u->user_login,
                        'compare' => '='
                    )
                )
            )
    );

    return $user_query->get_total();
}

function isms_get_total_leads_by_agents_single_distributor($id){
    $u = get_user_by('ID', $id);
    $user_query = new WP_User_Query(
        array(
            'role' => 'Agent' ,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'dist_id',
                    'value'   => $u->user_login,
                    'compare' => '='
                )
            )
        )
    );

    if ( ! empty( $user_query->get_results() ) ):
        $cnt = 0;
        foreach ($user_query->get_results() as $usr):
            $total = get_user_meta($usr->ID, 'reg_lead', true)?get_user_meta($usr->ID, 'reg_lead', true):0;
            $cnt = $cnt+$total;
        endforeach;

        return $cnt;
    else:
        return 0;
    endif;
}