<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 1/5/18
 * Time: 4:47 PM
 */


//add_action('genesis_header_right', 'fdb_top_login_menu', 20);

function fdb_top_login_menu(){
    if(is_user_logged_in()) :
    ?>
        <p class="navbar-text1 navbar-right sign-up-menu"><a href="<?=site_url('dashboard')?>">My Account</a> <a href="<?=wp_logout_url( home_url() )?>">Logout</a></p>
    <?php
    else :
        ?>
        <p class="navbar-text navbar-right sign-up-menu"><a href="<?=site_url('sign-in')?>">Log In</a> <a href="<?=site_url('sign-up')?>">Sign Up</a></p>
    <?php
    endif;
}


function fdb_get_state($country_code = 231){
    global $wpdb;
    //SELECT * FROM `wp_states` WHERE `country_id` = 231
    $states = $wpdb->get_results("SELECT * FROM `wp_states` WHERE `country_id` = {$country_code}");
    return $states;
}


function fdb_get_city($city_name = false){
    global $wpdb;
    global $wpdb;
    $state_id = $total = $wpdb->get_var("SELECT id FROM `wp_states` WHERE `country_id` = 231 AND `name` = '{$city_name}'");
    $cities = $wpdb->get_results("SELECT * FROM `wp_cities` WHERE `state_id` = {$state_id}");
    return $cities;
}

add_action('fed_get_city_data_processing', 'fed_get_city_data', 20);

function fed_get_city_data(){
    global $wpdb;
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


add_filter('pods_form_ui_field_pick_data_us_states', 'pod_get_city', 1, 10);

function pod_get_city($data){
    $states = fdb_get_state(231);
    $data = [];
    foreach ($states as $key => $s):
        $data[$s->name] = $s->name;
    endforeach;
    return $data;
}

add_action('fed_roofing_request_processing', 'fed_roofing_request_processing', 20);

function fed_roofing_request_processing() {
    global $wpdb;



    $newuser_email = false;

    $post = $_POST;
    if ( !is_user_logged_in() ) {
        $user_id = username_exists( $post['email_address'] );
        if(!$user_id){
            $user_data = get_user_by('email', $post['email_address']);
            $user_id = $user_data->ID;
        }

        if ( !$user_id and email_exists($post['email_address']) == false ) {
            $newuser_email = true;
            $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
            $user_id = wp_create_user(epic_data_escape($post['email_address']), $random_password, epic_data_escape($post['email_address']));
        }


    } else {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
    }


    $pod = pods('roofhub_request');
    unset($post['fed_ajax_hook']);
    unset($post['_wpnonce']);
    unset($post['_wp_http_referer']);
    unset($post['noreset']);
    $data = $post;
    error_log("Roofhub Request: " . print_r($data, true));
    $id = $pod->add($data);

    if($_FILES && isset($_FILES['roof_pictures'])):
        $aid =[];
        fixFilesArray($_FILES['roof_pictures']);
        foreach ($_FILES['roof_pictures'] as $fkey => $rpfiles):
            $aid[] = importImage($rpfiles);
        endforeach;
        $pod->save( 'roof_pictures', $aid, $id );
    endif;

    if($_FILES && isset($_FILES['roof_elevations_files'])):
        $aid =[];
        fixFilesArray($_FILES['roof_elevations_files']);
        foreach ($_FILES['roof_elevations_files'] as $ckey => $rpfiles):
            $aid[] = importImage($rpfiles);
        endforeach;
        $pod->save( 'roof_elevations_files', $aid, $id );
    endif;

    $pod->save( 'author', $user_id, $id );
    $pod->save( 'status', 'Request', $id );

    $pod->save( 'name', $post['first_name']."-".$post['state']."-".$post['city']."-".$post['zip_code'], $id );


    if($newuser_email):
        update_user_meta($user_id, 'first_name', $post['first_name']);
        update_user_meta($user_id, 'last_name', $post['last_name']);
        update_user_meta($user_id, 'address', $post['address']);
        update_user_meta($user_id, 'state', $post['state']);
        update_user_meta($user_id, 'city', $post['city']);
        update_user_meta($user_id, 'zip_code', $post['zip_code']);
        update_user_meta($user_id, 'phone_number', $post['phone_number']);
        $user_data = get_user_by('email', $post['email_address']);
        $u = new WP_User( $user_id );
        $u->set_role( 'customer' );
        $mail = new RhMail();
        $email_body = $mail->new_account_email($post['first_name'], $random_password,$post['email_address']);
        $subject = "RoofHub: New Account Details";
        $send_result = $mail->send_general_email( $email_body,  $post['email_address'], $subject, $user_data );
    endif;


    if($post['time_frame_needed'] == 'Urgent - need done immediately') :
        $contractors = get_featured_contractors($post['state']);
    else:
        $contractors = get_all_contractors($post['state']);
    endif;
    if($contractors):
        foreach($contractors as $conid):
            $conpod = pods( 'contractor', $conid );

            $as = $conpod->field( 'assign_roofing_request' , null, true  );
            $ass_id = [];
            if($as):
                foreach($as as $akey => $a):
                    $ass_id[] =  $a['id'];
                endforeach;
                array_push($ass_id, $id);
            else:
                $ass_id = $id;
            endif;


            $res = $conpod->save( ['assign_roofing_request'=>$ass_id] );

            $link = site_url("/dashboard/")."?menu_type=user&menu_slug=assigned_roofing_request&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=view&rid=".encrypt_decrypt('encrypt',$id);
            $conuser = $conpod->field( 'author' , null, true  );
            $mail = new RhMail();
            $email_body = $mail->new_req_to_contractor_email($post['first_name'], $link);
            $subject = "RoofHub: New Roofing request inboxed";
            $send_result = $mail->send_general_email( $email_body,  $conuser['user_email'], $subject );
        endforeach;
    endif;

    $mail = new RhMail();
    $link = site_url("/dashboard/")."?menu_type=user&menu_slug=your_roofing_request&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=view&rid=".encrypt_decrypt('encrypt',$id);

    $email_body = $mail->new_request_email_to_customer($post['first_name'], $link, $id);
    $subject = "RoofHub: Thanks for your request";
    $send_result = $mail->send_general_email( $email_body,  $post['email_address'], $subject, $user_data );

    $link = site_url("/dashboard/")."?menu_type=user&menu_slug=all_roofing_requests&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=view&rid=".encrypt_decrypt('encrypt',$id);
    $admin_email = get_option( 'admin_email' );
    $email_body = $mail->new_account_email($post['first_name'], $link, $id);
    $subject = "RoofHub: New Request Details";
    $send_result = $mail->send_general_email( $email_body,  $admin_email, $subject, $user_data );

    echo "Successfully send!";die;
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

    if($index == 'all_roofing_requests'){
        return false;
    }

    if($index == 'all_contractors'){
        return false;
    }

    if($index == 'settings'){
        return false;
    }

    if($index == 'my_advertisement'){
        return false;
    }

    if($index == 'payments_invoice'){
        return false;
    }

    if($index == 'your_roofing_requests'){
        return false;
    }

    if($index == 'contractor_information'){
        return false;
    }

    if($index == 'assigned_roofing_request'){
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
    //print_r($menu_items);
    //$display =
    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'all_roofing_requests' ) {
        $template = epic_community_template('admin-all-request', '/dashboard/');
        load_template( $template );
    }



    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'all_contractors' ) {
        $template = epic_community_template('admin-contractor-request', '/dashboard/');
        load_template( $template );
    }


    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'my_advertisement' ) {
        $template = epic_community_template('admin-advertisement', '/dashboard/');
        load_template( $template );
    }

    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'settings' ) {
        $template = epic_community_template('settings', '/dashboard/');
        load_template( $template );
    }

    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'payments_invoice' ) {
        $template = epic_community_template('payments', '/dashboard/');
        load_template( $template );
    }

    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'your_roofing_requests' ) {
        $template = epic_community_template('customer-rf-requests', '/dashboard/');
        load_template( $template );
    }

    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'contractor_information' ) {
        $template = epic_community_template('contractor-information', '/dashboard/');
        load_template( $template );
    }

    if ( isset($menu_items[$index]['menu_slug']) && $menu_items[$index]['menu_slug'] === 'assigned_roofing_request' ) {
        $template = epic_community_template('assigned-roofing-request', '/dashboard/');
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


add_action('fed_get_form_html_data_processing', 'fed_get_form_html_data_processing', 20);

function fed_get_form_html_data_processing(){
    global $wpdb;
    $ri = pods( 'referral_information' );

    ob_start();
   ?>
    <div class="ref-box" style="border-bottom: 1px solid #ccc; margin-bottom: 10px;">
        <a class="btn btn-danger js-del-htm pull-right" style="margin-bottom: 10px;">Delete-</a>
        <div class="form-group">
            <label for="fname"><?=$ri->pod_data['fields']['ref_name']['label']?><span style="color: red;">*</span></label>
            <input type="text" name="<?=$ri->pod_data['fields']['ref_name']['name']?>[]" placeholder="<?=$ri->pod_data['fields']['ref_name']['label']?>" class="<?=$ri->pod_data['fields']['ref_name']['name']?>" />
        </div>
        <div class="form-group">
            <label for="fname"><?=$ri->pod_data['fields']['ref_phone_number']['label']?><span style="color: red;">*</span></label>
            <input type="text" name="<?=$ri->pod_data['fields']['ref_phone_number']['name']?>[]" placeholder="<?=$ri->pod_data['fields']['ref_phone_number']['label']?>" class="<?=$ri->pod_data['fields']['ref_phone_number']['name']?>" />
        </div>
        <div class="form-group">
            <label for="fname"><?=$ri->pod_data['fields']['ref_email']['label']?></label>
            <input type="text" name="<?=$ri->pod_data['fields']['ref_email']['name']?>[]" placeholder="<?=$ri->pod_data['fields']['ref_email']['label']?>" />
        </div>
    </div>

    <?php
    $message = ob_get_contents();
    ob_end_clean();
    echo $message; die;
}


add_action('fed_contractor_request_processing', 'fed_contractor_request_processing', 20);

function fed_contractor_request_processing() {
    global $wpdb;



    $newuser_email = false;

    $post = $_POST;

    $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
    $user_id = wp_create_user(epic_data_escape($post['email_address']), $random_password, epic_data_escape($post['email_address']));



    $pod = pods('contractor');
    unset($post['fed_ajax_hook']);
    unset($post['_wpnonce']);
    unset($post['_wp_http_referer']);
    unset($post['noreset']);
    unset($post['redirect']);
    $data = $post;
    error_log("Contractor Request: " . print_r($data, true));
    $id = $pod->add($data);


    $pod->save( 'author', $user_id, $id );
    $pod->save( 'status', 0, $id );
    $pod->save( 'featured_contractor', "No", $id );
    $pod->save( 'premium_contractor', "No", $id );

    if($_FILES && isset($_FILES['company_logo'])):
        $aid =[];
        fixFilesArray($_FILES['company_logo']);
        foreach ($_FILES['company_logo'] as $ckey => $rpfiles):
            $aid[] = importImage($rpfiles);
        endforeach;
        $pod->save( 'company_logo', $aid, $id );
    endif;

    //if($newuser_email):
        update_user_meta($user_id, 'first_name', $post['contact_name']);
        update_user_meta($user_id, 'address', $post['business_address']);
        update_user_meta($user_id, 'state', $post['state']);
        update_user_meta($user_id, 'city', $post['city']);
        update_user_meta($user_id, 'zip_code', $post['zip_code']);
        update_user_meta($user_id, 'phone_number', $post['phone_number']);
        $u = new WP_User( $user_id );
        $u->set_role( 'contractor' );

    //endif;

    $wpdb->query("UPDATE `wp_users` SET user_status = 1 WHERE ID = '{$user_id}';");

    $ref_id = [];
    error_log('RF LOG:'.print_r($post['ref_name'], true));
    foreach ($post['ref_name'] as $rkey => $ref_name):
        $rdata = [];
        $pod_ri = pods('referral_information');
        $rdata['ref_name'] = $ref_name;

        if(isset($post['ref_phone_number'][$rkey])):
            $rdata['ref_phone_number'] = $post['ref_phone_number'][$rkey];
        endif;
        if(isset($post['ref_email'][$rkey])):
            $rdata['ref_email'] = $post['ref_email'][$rkey];
        endif;

        $rdata['author'] = $user_id;
        $rdata['contractors'] = $id;
        $rfid = $pod_ri->add($rdata);
        error_log('RF LOG:'.print_r($rfid, true));
        $ref_id[] = $rfid;
    endforeach;

    if(!empty($ref_id)):
        $pod->save( 'business_referrals', $ref_id, $id );
    endif;

    $u = new WP_User( $user_id );
    $u->set_role( 'contractor' );


    $mail = new RhMail();
    $link = site_url("/dashboard/")."?menu_type=user&menu_slug=all_contractors&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."display=view&rid=".encrypt_decrypt('encrypt',$id);
    $admin_email = get_option( 'admin_email' );
    $email_body = $mail->new_contractor_admin_email($post['name'], $link, $id);
    $subject = "RoofHub: New Contractor Request Details";
    $send_result = $mail->send_general_email( $email_body,  $admin_email, $subject );

    echo "Successfully send!";die;
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



function get_sponsor_adinfo()
{
    global $wpdb;
    $data = [];
    $userinfo = wp_get_current_user();
    $where = 'author.ID = "' . $userinfo->ID . '" AND';
    if(is_super_admin()):
        $where = '';
    endif;
    $params = array(
        'where' => ' '.$where.' t.is_delete = 0 ',
        'limit' => -1  // Return all rows
    );


    $advs = pods('advertisement', $params);
    $data['total_adv'] = $advs->total();
    $data['advs'] = $advs;

    $data['premium'] = 0;
    $data['sponsor'] = 0;
    /*$data['active'] = 0;
    $data['inactive'] = 0;*/

    $data['popup'] = false;

    $all_rows = $advs->data();

    $exist_data = $wpdb->get_row( "SELECT SUM(`used_card`) as used, SUM(`no_card`) as nocard FROM `wp_sponsor_member` WHERE user_id = '{$userinfo->ID}'" );

    if($exist_data && $exist_data->nocard):
        if($exist_data->nocard > $exist_data->used):
            $data['can_add'] = true;
        else:
            $data['can_add'] = false;
        endif;
    else:
        $data['can_add'] = false;
        $data['popup'] = true;
    endif;

    if (!empty($all_rows)) {
        foreach ($all_rows as $adv) {
            if($adv->is_premium){
                $data['premium'] = $data['premium']+1;
            } else {
                $data['sponsor'] = $data['sponsor']+1;
            }

           /* if($adv->status){
                $data['active'] = $data['active']+1;
            } else {
                $data['inactive'] = $data['inactive']+1;
            }*/
        }
    }

    $exist_data = $wpdb->get_row( "SELECT `type` FROM `wp_sponsor_member` WHERE user_id = '{$userinfo->ID}'  ORDER BY `date` DESC LIMIT 1" );
    $data['type'] = $exist_data && $exist_data->type?$exist_data->type:false;


    return $data;
}


function get_payments_info(){
    $data = [];
    $userinfo = wp_get_current_user();
    $where = 'author.ID = "' . $userinfo->ID . '" AND';
    if(is_super_admin()):
        $where = '';
    endif;
    $params = array(
        'where' => ' '.$where.' 1 = 1 ',
        'limit' => -1  // Return all rows
    );


    $advs = pods('payment', $params);

    $data['payment'] = $advs;

    return $data;
}

function no_image_url(){
    return plugins_url('/_inc/images/noimage.png', BC_FED_PLUGIN );
}



function get_sponsor_user_type(){
    $current_user_id = get_current_user_id();

    $type = get_user_meta($current_user_id,'sponsor_type', true);

    return $type;
}

/**
 *
 */
function get_sponsor_existing_cards_package(){
    global $wpdb;
    $userinfo = wp_get_current_user();
    $results = $wpdb->get_results("SELECT * FROM `wp_sponsor_member` WHERE no_card > used_card AND user_id = $userinfo->ID");
    $data = [];
    foreach ($results as $key => $result):
        $people = pods( 'adv_package', $result->ad_id );
        $adv = $people->row();
        $result->adinfo = $adv;
        $data[] = $result;
    endforeach;

    return $data;
}


function get_users_by_state_city($state = false, $city = false){
    global $wpdb;

    if($state):
        $state = array(
            'key'     => 'state',
            'value'   => $state,
            'compare' => '='
        );
    else:
        $state = array(
            'key'     => 'state',
            'value'   => '',
            'compare' => '!='
        );
    endif;

    if($city):
        $city = array(
            'key'     => 'city',
            'value'   => $city,
            'compare' => '='
        );
    else:
        $city = array(
            'key'     => 'city',
            'value'   => '',
            'compare' => '!='
        );
    endif;

    $args = array(
        'role' => 'contractor',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'state',
                'value'   => false,
                'compare' => '='
            ),
            array(
                'key'     => 'city',
                'value'   => '',
                'compare' => '!='
            )
        )
    );
    $user_query = new WP_User_Query( $args );

    $data = [];
    if ( ! empty( $user_query->get_results() ) ) {
        $com = [];
        foreach ( $user_query->get_results() as $user ) {
            $data[$user->ID] = get_user_meta($user->ID, 'first_name', true). " ".get_user_meta($user->ID, 'last_name', true);
        }

    }

    return $data;
}


function get_featured_contractors($state = false){
    $where = ' 1=1  AND (t.featured_contractor = 1 OR t.premium_contractor = 1) ';

    if($state):
        $where .= ' AND t.state = "'.$state.'" ';
    endif;
    $params = array(
            'where' => $where,
            'limit'   => -1  // Return all rows
    );

    $contractors = pods( 'contractor', $params );
    $cons = [];

    if ( 0 < $contractors->total() ):
        $i = 1;
        while ( $contractors->fetch() ):
            $cons[] = $contractors->display('id');
        endwhile;
   endif;

   return $cons;
}

function get_all_contractors($state = false){
    $where = ' 1=1  AND (t.featured_contractor = 0) ';

    if($state):
        $where .= ' AND t.state = "'.$state.'" ';
    endif;
    $params = array(
        'where' => $where,
        'limit'   => -1  // Return all rows
    );

    $contractors = pods( 'contractor', $params );
    $cons = [];

    if ( 0 < $contractors->total() ):
        $i = 1;
        while ( $contractors->fetch() ):
            $cons[] = $contractors->display('id');
        endwhile;
    endif;

    return $cons;
}