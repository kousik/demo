<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 16/5/18
 * Time: 1:05 AM
 */

//Actions
add_action('fed_update_email_processing', 'fed_update_email_processing', 20);
add_action('fed_update_password_processing', 'fed_update_password_processing', 20);
add_action('fed_global_data_update_processing', 'fed_global_data_update_processing', 20);


add_action('fed_get_all_agents_processing', 'fed_get_all_agents_processing', 20);
add_action('fed_delete_user_processing', 'fed_delete_user_processing', 20);
add_action('fed_agent_data_update_processing', 'fed_agent_data_update_processing', 20);

add_action('fed_get_all_customers_processing', 'fed_get_all_customers_processing', 20);
add_action('fed_customer_data_update_processing', 'fed_customer_data_update_processing', 20);
    
add_action('fed_user_data_update_processing', 'fed_user_data_update_processing', 20);
    
add_action('fed_admin_to_user_mail_processing', 'fed_admin_to_user_mail_processing', 20);

add_action('fed_send_global_email_processing', 'fed_send_global_email_processing', 20);

add_action('fed_send_email_to_admin_processing', 'fed_send_email_to_admin_processing', 20);
add_action('fed_exchange_agent_processing', 'fed_exchange_agent_processing', 20);

add_action('fed_get_all_distributors_processing', 'fed_get_all_distributors_processing', 20);

add_action('fed_distributor_data_update_processing', 'fed_distributor_data_update_processing', 20);

add_action('fed_exchange_distributor_processing', 'fed_exchange_distributor_processing', 20);

//Functions


/**
 * AJAX: email update
 */
function fed_update_email_processing(){
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'update-email-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;

    if ( ! is_user_logged_in()) {
        echo "-1<p class='box alert'>Unauthorized access!</p>";die;
    }

    if ( ! $_POST['user_email'] ):
        echo "-1<p class='box alert'>Please enter E-mail/Login id!</p>";die;
    endif;

    $userinfo = wp_get_current_user();
    $user_data = get_user_by( 'email', $_POST['user_email'] );

    $user = array('ID' => $userinfo->ID);
    if($user_data && ($user_data->ID != $userinfo->ID) ){
        echo "-1<p class='box alert'>E-mail already have taken by another user.</p>";die;
    } else {
        $user['user_login'] = $_POST['user_email'];
        $user['user_email'] = $_POST['user_email'];
    }

    $update_result = wp_update_user($user);
    if( ! is_wp_error( $update_result ) ) {
        echo "<p class='box tick'>E-mail/Login id have been successfully updated.</p>";die;
    } else {
        echo "-1<p class='box alert'>Some error happen, please try after later.</p>";die;
    }

    die;
}

/**
 * AJAX: password update
 */
function fed_update_password_processing(){
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'update-password-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;

    if ( ! is_user_logged_in()) {
        echo "-1<p class='box alert'>Unauthorized access!</p>";die;
    }

    if ( $_POST['password'] && !$_POST['conf_password']):
        echo "-1<p class='box alert'>Please enter password!</p>";die;
    endif;

    if ($_POST['password'] != $_POST['conf_password']) :
        echo '-1<p class=\'box alert\'>Password mismatch!</p>';
        die;
    endif;

    if ( strlen($_POST['password']) < 6) :
        echo '-1<p class=\'box alert\'>Password must be at least 6 characters!</p>';
        die;
    endif;


    $userinfo = wp_get_current_user();
    $user = array('ID' => $userinfo->ID, 'user_pass' => $_POST['password']);

    if(current_user_can('agent')):
        update_user_meta($userinfo->ID, 'pwd', encrypt_decrypt('encrypt', $_POST['password']));
    endif;

    if(current_user_can('distributor')):
        update_user_meta($userinfo->ID, 'pwd', encrypt_decrypt('encrypt', $_POST['password']));
    endif;

    if(current_user_can('customer')):
        update_user_meta($userinfo->ID, 'pwd', encrypt_decrypt('encrypt', $_POST['password']));
    endif;

    $update_result = wp_update_user($user);
    if( ! is_wp_error( $update_result ) ) {
        echo "<p class='box tick'>Thank you! Your password has been successfully changed.</p>";die;
    } else {
        echo "-1<p class='box alert'>Some error happen, please try after later.</p>";die;
    }

    die;
}

/**
 * AJAX: Global update
 */
function fed_global_data_update_processing(){
    global $wpdb;
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'agent-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;

    if($_POST['type'] == "agents"):
        if ( !$_POST['agent_number']):
            echo "-1<p class='box alert'>Please enter number of agent want to create!</p>";die;
        endif;

        if ( !$_POST['state']):
            echo "-1<p class='box alert'>Please select a state!</p>";die;
        endif;

        $state_code = $wpdb->get_var("SELECT code FROM `wp_states` WHERE `country_id` = 101 AND `name` = '{$_POST['state']}'");

        for($i = 1; $i <= $_POST['agent_number']; $i++):
            $user_name = isms_gen_id($len = 12,$name_cast = 'AG', $state_code);
            $user_email = strtolower($user_name)."@ismobilesecurity.com";
            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $user_id = wp_create_user( $user_name, $random_password, $user_email );
            //$wpdb->query("UPDATE `wp_users` SET user_status = 1 WHERE ID = '{$user_id}';");
            $pwd = encrypt_decrypt('encrypt', $random_password);
            update_user_meta($user_id, 'pwd', $pwd);
            update_user_meta($user_id, 'target_lead', $_POST['leads_number']?$_POST['leads_number']:50);
            update_user_meta($user_id, 'state', $_POST['state']);
            update_user_meta($user_id, 'state_code', $state_code);
            $u = new WP_User( $user_id );
            $u->set_role( 'agent' );
        endfor;
        echo "<p class='box tick'>Thank you! Users has been created successfully!.</p>";die;
    endif;



    if($_POST['type'] == "distributor"):
        if ( !$_POST['dist_number']):
            echo "-1<p class='box alert'>Please enter number of distributors want to create!</p>";die;
        endif;

        if ( !$_POST['state']):
            echo "-1<p class='box alert'>Please select a state!</p>";die;
        endif;

        $state_code = $wpdb->get_var("SELECT code FROM `wp_states` WHERE `country_id` = 101 AND `name` = '{$_POST['state']}'");


        for($i = 1; $i <= $_POST['dist_number']; $i++):
            $user_name = isms_gen_id($len = 12,$name_cast = 'DT', $state_code);
            $user_email = strtolower($user_name)."@ismobilesecurity.com";
            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $user_id = wp_create_user( $user_name, $random_password, $user_email );
            //$wpdb->query("UPDATE `wp_users` SET user_status = 1 WHERE ID = '{$user_id}';");
            $pwd = encrypt_decrypt('encrypt', $random_password);
            update_user_meta($user_id, 'pwd', $pwd);
            update_user_meta($user_id, 'state', $_POST['state']);
            update_user_meta($user_id, 'state_code', $state_code);
            $u = new WP_User( $user_id );
            $u->set_role( 'distributor' );
            $dist_id = $user_name;

            if($user_id):
                if($_POST['agent_number'] && $_POST['agent_number'] > 0):
                    for($i = 1; $i <= $_POST['agent_number']; $i++):
                        $user_name = isms_gen_id($len = 12,$name_cast = 'AG', $state_code);
                        $user_email = strtolower($user_name)."@ismobilesecurity.com";
                        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                        $user_id = wp_create_user( $user_name, $random_password, $user_email );
                        //$wpdb->query("UPDATE `wp_users` SET user_status = 1 WHERE ID = '{$user_id}';");
                        $pwd = encrypt_decrypt('encrypt', $random_password);
                        update_user_meta($user_id, 'pwd', $pwd);
                        update_user_meta($user_id, 'target_lead', $_POST['leads_number']?$_POST['leads_number']:50);
                        update_user_meta($user_id, 'state', $_POST['state']);
                        update_user_meta($user_id, 'state_code', $state_code);
                        update_user_meta($user_id, 'dist_id', $dist_id);
                        $u = new WP_User( $user_id );
                        $u->set_role( 'agent' );
                    endfor;
                endif;
            endif;
        endfor;

        echo "<p class='box tick'>Thank you! Users has been created successfully!.</p>";die;
    endif;

    echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
}


/**
 * AJAX: get all agents update
 */
function fed_get_all_agents_processing(){
    global $wpdb, $blog_id;


    // initilize all variable
    $params = $columns = $totalRecords = $data = array();

    $params = $_REQUEST;

    //define index of column
    $columns = array(
        1 => 'user_login',
        9 => 'user_status'
    );

    $where = $join = $groupby = $sqlTot = $sqlRec = "";

    $where = " WHERE 1=1 ";


    if( !empty($params['search']['value']) ):
        $join .= "  INNER JOIN wp_usermeta AS mt1 ON ( wp_users.ID = mt1.user_id ) ";
        $search_term = esc_attr($params['search']['value']);
        $where .= "AND ( 
                      ( 
                        ( 
                          ( wp_usermeta.meta_key = 'first_name' AND wp_usermeta.meta_value LIKE '%{$search_term}%' ) 
                          OR 
                          ( wp_usermeta.meta_key = 'dist_id' AND wp_usermeta.meta_value LIKE '%{$search_term}%' ) 
                          OR 
                          ( wp_usermeta.meta_key = 'target_start' AND wp_usermeta.meta_value LIKE '%{$search_term}%' ) 
                          OR 
                          ( wp_usermeta.meta_key = 'target_end' AND wp_usermeta.meta_value LIKE '%{$search_term}%' )
                        ) 
                        OR 
                        ( 
                          ( 
                            (user_login LIKE '%{$search_term}%' OR user_email LIKE '%{$search_term}%')
                          )
                        )
                      )
                    ) AND ( mt1.meta_key = 'wp_capabilities' AND mt1.meta_value LIKE '%Agent%' )";
    else:
         $where .= " AND ( 
                      ( 
                        ( wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%Agent%' )
                      )
                    )";
    endif;

    if($params['columns'][10]['search']['value'] >= 1){

        if($params['columns'][10]['search']['value'] == 2) {
            $where .= " AND  user_status =  1  ";
        }

        if($params['columns'][10]['search']['value'] == 1) {
            $where .= " AND  user_status =  0 ";
        }


    }


    $args['number'] = $params['start'];
    $args['offset'] = $params['length'];

    $order_by = $columns[$params['order'][0]['column']]?$columns[$params['order'][0]['column']]:'ID';

    $sqlRec = "SELECT SQL_CALC_FOUND_ROWS wp_users.* FROM wp_users INNER JOIN wp_usermeta ON ( wp_users.ID = wp_usermeta.user_id ) {$join} {$where} GROUP BY wp_users.ID ORDER BY {$order_by} {$params['order'][0]['dir']} ";

    $sqlTot = $wpdb->get_results($sqlRec);
    $totalRecords = 0;
    if($sqlTot):
        $totalRecords = count($sqlTot);
    endif;

    $sqlRec = $sqlRec." LIMIT {$params['start']}, {$params['length']}";
    $queryRecords = $wpdb->get_results($sqlRec);


    $data = [];
    if ( $queryRecords ):
        $i = 1;
        foreach ($queryRecords as $user):
            $row = [];
            $row['row_id'] = $i;
            $row['user_login'] = $user->user_login;
            $row['pwd'] = encrypt_decrypt('decrypt', get_user_meta($user->ID, 'pwd', true));
            $row['first_name'] = get_user_meta($user->ID, 'first_name', true)?get_user_meta($user->ID, 'first_name', true):"--";
            $dist_id = get_user_meta($user->ID, 'dist_id', true);
            if($dist_id):
                $u = get_user_by('login', $dist_id);
                $row['dist_id'] =  get_user_meta($u->ID, 'first_name', true)?get_user_meta($u->ID, 'first_name', true)."<br>(ID: ".$u->user_login.")":"ID: ".$u->user_login;
            else:
                $row['dist_id'] =  "N/A";
            endif;
            $row['target_lead'] =  get_user_meta($user->ID, 'target_lead', true)?get_user_meta($user->ID, 'target_lead', true):0;
            $row['reg_lead'] =  get_user_meta($user->ID, 'reg_lead', true)?get_user_meta($user->ID, 'reg_lead', true):0;
            $row['target_start'] = get_user_meta($user->ID, 'target_start', true)?get_user_meta($user->ID, 'target_start', true):"--";
            $row['target_end'] = get_user_meta($user->ID, 'target_end', true)?get_user_meta($user->ID, 'target_end', true):"--";
            $row['user_status'] = $user->user_status == 1?'<span class="label label-danger">De-Active</span>':'<span class="label label-success">Active</span>';

            $view_link = site_url("/dashboard/")."?menu_type=user&menu_slug=agents&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=agview&rid=".encrypt_decrypt('encrypt',$user->ID);
            $edit_link = site_url("/dashboard/")."?menu_type=user&menu_slug=agents&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=agedit&rid=".encrypt_decrypt('encrypt',$user->ID);
            $row['actions'] =  '<a class="btn btn-info btn-xs" data-action="user_view" href="'.$view_link.'" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn btn-warning btn-xs" data-action="user_edit" href="'.$edit_link.'" role="button" alt="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a class="btn btn-danger btn-xs js-request-delete" href="javascript://" role="button" alt="Delete" data-req="'.encrypt_decrypt('encrypt',$user->ID).'" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
            $data[] = $row;
            $i++;
        endforeach;
    endif;

    $json_data = array(
        "draw"            => intval( $params['draw'] ),
        "recordsTotal"    => intval( $totalRecords ),
        "recordsFiltered" => intval($totalRecords),
        "data"            => $data   // total data array
    );

    echo json_encode($json_data);  // send data as json format
    die;
}

/**
 *AJAX: user delete
 */
function fed_delete_user_processing(){
    $id = encrypt_decrypt('decrypt', $_POST['id']);
    require_once(ABSPATH.'wp-admin/includes/user.php' );
    $res = wp_delete_user( $id );
    $result = [];
    if($res):
        $result['msg'] = '<p class="box tick">User hes been deleted successfully!</p>';
        $result['delete'] = true;
    else:
        $result['msg'] = '<p class="box alert">Some error happen. Please try again later!</p>';
        $result['delete'] = false;
    endif;
    echo json_encode($result);die;
}

/**
 * AJAX: Agent update
 */
function fed_agent_data_update_processing(){
    global $wpdb;

    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'agent-update-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;

    $uid = encrypt_decrypt('decrypt', $_POST['id']);

    if ( !$_POST['first_name']):
        echo "-1<p class='box alert'>Please enter agent name!</p>";die;
    endif;

    if ( !$_POST['user_email']):
        echo "-1<p class='box alert'>Please enter agent email id!</p>";die;
    endif;

    if ( !is_email($_POST['user_email'])):
        echo "-1<p class='box alert'>Please enter agent valid email id!</p>";die;
    endif;
    $user = get_user_by('email', $_POST['user_email']);

    if( $user && ($uid != $user->ID) ):
        echo "-1<p class='box alert'>Email id already taken, please enter different email!</p>";die;
    endif;

    if ( !$_POST['mobile_number']):
        echo "-1<p class='box alert'>Please enter agent mobile number!</p>";die;
    endif;


    $user_data = get_user_by('ID', $uid);
    $pwd = wp_update_user( array( 'ID' => $uid, 'user_email' => $_POST['user_email'] ) );
    $new_pwd = $_POST['user_pass'];

    // create the wp hasher to add some salt to the md5 hash
    require_once( ABSPATH . '/wp-includes/class-phpass.php');
    $wp_hasher = new PasswordHash(8, TRUE);
    // check that provided password is correct
    $check_pwd = $wp_hasher->CheckPassword($new_pwd, $user_data->user_pass);

    if(!$check_pwd):
        wp_update_user( array( 'ID' => $uid, 'user_pass' => $new_pwd ) );
        update_user_meta($uid, 'pwd', encrypt_decrypt('encrypt', $new_pwd) );
    endif;

    wp_update_user( array( 'ID' => $uid, 'user_email' => $_POST['user_email'] ) );

    update_user_meta($uid, 'first_name', $_POST['first_name']);
    update_user_meta($uid, 'dist_id', $_POST['dist_id']);
    update_user_meta($uid, 'target_lead', $_POST['target_lead']);
    update_user_meta($uid, 'target_start', $_POST['target_start']);
    update_user_meta($uid, 'target_end', $_POST['target_end']);
    update_user_meta($uid, 'mobile_number', $_POST['mobile_number']);
    update_user_meta($uid, 'address1', $_POST['address1']);
    update_user_meta($uid, 'address2', $_POST['address2']);
    update_user_meta($uid, 'state', $_POST['state']);
    update_user_meta($uid, 'city', $_POST['city']);
    update_user_meta($uid, 'pin', $_POST['pin']);
    $wpdb->query("UPDATE `wp_users` SET user_status = {$_POST['user_status']} WHERE ID = '{$uid}';");
    
    if ( ! empty( $_FILES['user_avatar']['name'] ) ) :
        isms_user_avater_upload($_FILES, $uid);
    endif;
    
    echo "<p class='box tick'>User agent successfully updated!</p>";die;
}


/**
 * AJAX: get all agents update
 */
function fed_get_all_customers_processing(){
    global $wpdb, $blog_id;


    // initilize all variable
    $params = $columns = $totalRecords = $data = array();

    $params = $_REQUEST;

    //define index of column
    $columns = array(
        1 => 'user_login',
        9 => 'user_status'
    );

    $where = $join = $groupby = $sqlTot = $sqlRec = "";

    $where = " WHERE 1=1 ";


    if( !empty($params['search']['value']) ):
        $join .= "  INNER JOIN wp_usermeta AS mt1 ON ( wp_users.ID = mt1.user_id ) ";
        $search_term = esc_attr($params['search']['value']);
        $where .= "AND ( 
                      ( 
                        ( 
                          ( wp_usermeta.meta_key = 'first_name' AND wp_usermeta.meta_value LIKE '%{$search_term}%' ) 
                          OR 
                          ( wp_usermeta.meta_key = 'agent_id' AND wp_usermeta.meta_value LIKE '%{$search_term}%' ) 
                          OR 
                          ( wp_usermeta.meta_key = 'mobile_number' AND wp_usermeta.meta_value LIKE '%{$search_term}%' ) 
                          OR 
                          ( wp_usermeta.meta_key = 'state' AND wp_usermeta.meta_value LIKE '%{$search_term}%' )
                          OR 
                          ( wp_usermeta.meta_key = 'city' AND wp_usermeta.meta_value LIKE '%{$search_term}%' )
                          OR 
                          ( wp_usermeta.meta_key = 'pin' AND wp_usermeta.meta_value LIKE '%{$search_term}%' )
                        ) 
                        OR 
                        ( 
                          ( 
                            (user_login LIKE '%{$search_term}%' OR user_email LIKE '%{$search_term}%')
                          )
                        )
                      )
                    ) AND ( mt1.meta_key = 'wp_capabilities' AND mt1.meta_value LIKE '%Customer%' )";
    else:
        $where .= " AND ( 
                      ( 
                        ( wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%Customer%' )
                      )
                    )";
    endif;

    if($params['columns'][10]['search']['value'] >= 1){

        if($params['columns'][10]['search']['value'] == 2) {
            $where .= " AND  user_status =  1  ";
        }

        if($params['columns'][10]['search']['value'] == 1) {
            $where .= " AND  user_status =  0 ";
        }


    }


    $args['number'] = $params['start'];
    $args['offset'] = $params['length'];

    $order_by = $columns[$params['order'][0]['column']]?$columns[$params['order'][0]['column']]:'ID';

    $sqlRec = "SELECT SQL_CALC_FOUND_ROWS wp_users.* FROM wp_users INNER JOIN wp_usermeta ON ( wp_users.ID = wp_usermeta.user_id ) {$join} {$where} GROUP BY wp_users.ID ORDER BY {$order_by} {$params['order'][0]['dir']} ";

    $sqlTot = $wpdb->get_results($sqlRec);
    $totalRecords = 0;
    if($sqlTot):
        $totalRecords = count($sqlTot);
    endif;

    $sqlRec = $sqlRec." LIMIT {$params['start']}, {$params['length']}";
    $queryRecords = $wpdb->get_results($sqlRec);


    $data = [];
    if ( $queryRecords ):
        $i = 1;
        foreach ($queryRecords as $user):
            $row = [];
            $row['row_id'] = $i;
            $row['user_login'] = $user->user_login;
            $row['pwd'] = get_user_meta($user->ID, 'pwd', true)?encrypt_decrypt('decrypt', get_user_meta($user->ID, 'pwd', true)):"N/A";
            $row['first_name'] = get_user_meta($user->ID, 'first_name', true)?get_user_meta($user->ID, 'first_name', true):"--";
            $agent_id = get_user_meta($user->ID, 'agent_id', true);
            if($agent_id):
                $u = get_user_by('login', $agent_id);
                $row['agent_id'] =  get_user_meta($u->ID, 'first_name', true)?get_user_meta($u->ID, 'first_name', true)."<br>(ID: ".$u->user_login.")":"ID: ".$u->user_login;
            else:
                $row['agent_id'] =  "--";
            endif;
            $row['mobile_number'] =  get_user_meta($user->ID, 'mobile_number', true)?get_user_meta($user->ID, 'mobile_number', true):"--";
            $row['state'] =  get_user_meta($user->ID, 'state', true)?get_user_meta($user->ID, 'state', true):"--";
            $row['city'] = get_user_meta($user->ID, 'city', true)?get_user_meta($user->ID, 'city', true):"--";
            $row['pin'] = get_user_meta($user->ID, 'pin', true)?get_user_meta($user->ID, 'pin', true):"--";
            $row['user_status'] = $user->user_status == 1?'<span class="label label-danger">De-Active</span>':'<span class="label label-success">Active</span>';

            $view_link = site_url("/dashboard/")."?menu_type=user&menu_slug=customers&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=custview&rid=".encrypt_decrypt('encrypt',$user->ID);
            $edit_link = site_url("/dashboard/")."?menu_type=user&menu_slug=customers&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=custedit&rid=".encrypt_decrypt('encrypt',$user->ID);

            $row['actions'] =  '<a class="btn btn-info btn-xs" data-action="user_view" href="'.$view_link.'" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn btn-warning btn-xs" data-action="user_edit" href="'.$edit_link.'" role="button" alt="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a class="btn btn-danger btn-xs js-request-delete" href="javascript://" role="button" alt="Delete" data-req="'.encrypt_decrypt('encrypt',$user->ID).'" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
            $data[] = $row;
            $i++;
        endforeach;
    endif;

    $json_data = array(
        "draw"            => intval( $params['draw'] ),
        "recordsTotal"    => intval( $totalRecords ),
        "recordsFiltered" => intval($totalRecords),
        "data"            => $data   // total data array
    );

    echo json_encode($json_data);  // send data as json format
    die;
}

/**
 * AJAX: Agent update
 */
function fed_customer_data_update_processing(){
    global $wpdb;

    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'customer-update-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;

    $uid = encrypt_decrypt('decrypt', $_POST['id']);

    if ( !$_POST['first_name']):
        echo "-1<p class='box alert'>Please enter agent name!</p>";die;
    endif;

    if ( !$_POST['user_email']):
        echo "-1<p class='box alert'>Please enter customer email id!</p>";die;
    endif;

    if ( !is_email($_POST['user_email'])):
        echo "-1<p class='box alert'>Please enter agent valid email id!</p>";die;
    endif;
    $user = get_user_by('email', $_POST['user_email']);

    if( $user && ($uid != $user->ID) ):
        echo "-1<p class='box alert'>Email id already taken, please enter different email!</p>";die;
    endif;

    if ( !$_POST['mobile_number']):
        echo "-1<p class='box alert'>Please enter customer mobile number!</p>";die;
    endif;


    $user_data = get_user_by('ID', $uid);
    $pwd = encrypt_decrypt('decrypt', get_user_meta($uid, 'pwd', true));
    $new_pwd = $_POST['user_pass'];

    // create the wp hasher to add some salt to the md5 hash
    require_once( ABSPATH . '/wp-includes/class-phpass.php');
    $wp_hasher = new PasswordHash(8, TRUE);
    // check that provided password is correct
    $check_pwd = $wp_hasher->CheckPassword($new_pwd, $user_data->user_pass);

    if(!$check_pwd):
        wp_update_user( array( 'ID' => $uid, 'user_pass' => $new_pwd ) );
        update_user_meta($uid, 'pwd', encrypt_decrypt('encrypt', $new_pwd) );
    endif;

    wp_update_user( array( 'ID' => $uid, 'user_email' => $_POST['user_email'] ) );

    update_user_meta($uid, 'first_name', $_POST['first_name']);
    update_user_meta($uid, 'mobile_number', $_POST['mobile_number']);
    update_user_meta($uid, 'address1', $_POST['address1']);
    update_user_meta($uid, 'address2', $_POST['address2']);
    update_user_meta($uid, 'state', $_POST['state']);
    update_user_meta($uid, 'city', $_POST['city']);
    update_user_meta($uid, 'pin', $_POST['pin']);

    $old_agid = get_user_meta($uid, 'agent_id', true);
    if($old_agid != $_POST['agent_id']):
        $new_ag =get_user_by('login', $_POST['agent_id']);
        $reg_lead = get_user_meta($new_ag->ID, "reg_lead", true)?get_user_meta($new_ag->ID, "reg_lead", true):0;
        $new_reg_lead = $reg_lead + 1;
        update_user_meta($new_ag->ID, 'reg_lead', $new_reg_lead);
    endif;

    if($old_agid && ($old_agid != $_POST['agent_id']) ):
        $old_ag =get_user_by('login', $old_agid);
        $reg_lead = get_user_meta($old_ag->ID, "reg_lead", true)?get_user_meta($old_ag->ID, "reg_lead", true):0;
        $new_reg_lead = $reg_lead - 1;
        update_user_meta($old_ag->ID, 'reg_lead', $new_reg_lead);
    endif;

    update_user_meta($uid, 'agent_id', $_POST['agent_id']);

    $wpdb->query("UPDATE `wp_users` SET user_status = {$_POST['user_status']} WHERE ID = '{$uid}';");
    
    if ( ! empty( $_FILES['user_avatar']['name'] ) ) :
        isms_user_avater_upload($_FILES, $uid);
    endif;
    
    echo "<p class='box tick'>Customer successfully updated!</p>";die;
}


function fed_user_data_update_processing(){
    global $wpdb;
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'userupdate-update-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;
    
    $uid = encrypt_decrypt('decrypt', $_POST['id']);
    
    if($_POST['type'] == "agent"):
        if ( !$_POST['first_name']):
            echo "-1<p class='box alert'>Please enter agent name!</p>";die;
        endif;
    
        if ( !$_POST['user_email']):
            echo "-1<p class='box alert'>Please enter email id!</p>";die;
        endif;
    
        if ( !is_email($_POST['user_email'])):
            echo "-1<p class='box alert'>Please enter valid email id!</p>";die;
        endif;
        $user = get_user_by('email', $_POST['user_email']);
    
        if( $user && ($uid != $user->ID) ):
            echo "-1<p class='box alert'>Email id already taken, please enter different email!</p>";die;
        endif;
    
        if ( !$_POST['mobile_number']):
            echo "-1<p class='box alert'>Please enter mobile number!</p>";die;
        endif;
    
        if ( !$_POST['address1']):
            echo "-1<p class='box alert'>Please enter address1!</p>";die;
        endif;
    
        if ( !$_POST['state']):
            echo "-1<p class='box alert'>Please enter state!</p>";die;
        endif;
    
        if ( !$_POST['city']):
            echo "-1<p class='box alert'>Please enter city!</p>";die;
        endif;
    
        if ( !$_POST['pin']):
            echo "-1<p class='box alert'>Please enter pin!</p>";die;
        endif;
    
        wp_update_user( array( 'ID' => $uid, 'user_email' => $_POST['user_email'] ) );
        update_user_meta($uid, 'first_name', $_POST['first_name']);
        update_user_meta($uid, 'mobile_number', $_POST['mobile_number']);
        update_user_meta($uid, 'address1', $_POST['address1']);
        update_user_meta($uid, 'address2', $_POST['address2']);
        update_user_meta($uid, 'state', $_POST['state']);
        update_user_meta($uid, 'city', $_POST['city']);
        update_user_meta($uid, 'pin', $_POST['pin']);
        if ( ! empty( $_FILES['user_avatar']['name'] ) ) :
            isms_user_avater_upload($_FILES, $uid);
        endif;
        echo "<p class='box tick'>Account successfully updated!</p>";die;
    endif;
    
    
    if($_POST['type'] == "distributor"):
        if ( !$_POST['first_name']):
            echo "-1<p class='box alert'>Please enter agent name!</p>";die;
        endif;
        
        if ( !$_POST['user_email']):
            echo "-1<p class='box alert'>Please enter email id!</p>";die;
        endif;
        
        if ( !is_email($_POST['user_email'])):
            echo "-1<p class='box alert'>Please enter valid email id!</p>";die;
        endif;
        $user = get_user_by('email', $_POST['user_email']);
        
        if( $user && ($uid != $user->ID) ):
            echo "-1<p class='box alert'>Email id already taken, please enter different email!</p>";die;
        endif;
        
        if ( !$_POST['mobile_number']):
            echo "-1<p class='box alert'>Please enter mobile number!</p>";die;
        endif;
        
        if ( !$_POST['address1']):
            echo "-1<p class='box alert'>Please enter address1!</p>";die;
        endif;
        
        if ( !$_POST['state']):
            echo "-1<p class='box alert'>Please enter state!</p>";die;
        endif;
        
        if ( !$_POST['city']):
            echo "-1<p class='box alert'>Please enter city!</p>";die;
        endif;
    
        if ( !$_POST['pin']):
            echo "-1<p class='box alert'>Please enter pin!</p>";die;
        endif;
        
        wp_update_user( array( 'ID' => $uid, 'user_email' => $_POST['user_email'] ) );
        update_user_meta($uid, 'first_name', $_POST['first_name']);
        update_user_meta($uid, 'mobile_number', $_POST['mobile_number']);
        update_user_meta($uid, 'address1', $_POST['address1']);
        update_user_meta($uid, 'address2', $_POST['address2']);
        update_user_meta($uid, 'state', $_POST['state']);
        update_user_meta($uid, 'city', $_POST['city']);
        update_user_meta($uid, 'pin', $_POST['pin']);
        if ( ! empty( $_FILES['user_avatar']['name'] ) ) :
            isms_user_avater_upload($_FILES, $uid);
        endif;
        echo "<p class='box tick'>Account successfully updated!</p>";die;
    endif;
    
    if($_POST['type'] == "customer"):
        if ( !$_POST['first_name']):
            echo "-1<p class='box alert'>Please enter agent name!</p>";die;
        endif;
    
        if ( !$_POST['user_email']):
            echo "-1<p class='box alert'>Please enter customer email id!</p>";die;
        endif;
    
        if ( !is_email($_POST['user_email'])):
            echo "-1<p class='box alert'>Please enter agent valid email id!</p>";die;
        endif;
        $user = get_user_by('email', $_POST['user_email']);
    
        if( $user && ($uid != $user->ID) ):
            echo "-1<p class='box alert'>Email id already taken, please enter different email!</p>";die;
        endif;
    
        /*if ( !$_POST['mobile_number']):
            echo "-1<p class='box alert'>Please enter customer mobile number!</p>";die;
        endif; */
    
        if ( !$_POST['gender']):
            echo "-1<p class='box alert'>Please enter your gender!</p>";die;
        endif;
    
        if ( !$_POST['dob']):
            echo "-1<p class='box alert'>Please enter your date of birth!</p>";die;
        endif;
    
        if ( !$_POST['address1']):
            echo "-1<p class='box alert'>Please enter address1!</p>";die;
        endif;
    
        if ( !$_POST['state']):
            echo "-1<p class='box alert'>Please enter state!</p>";die;
        endif;
    
        if ( !$_POST['city']):
            echo "-1<p class='box alert'>Please enter city!</p>";die;
        endif;
    
        if ( !$_POST['pin']):
            echo "-1<p class='box alert'>Please enter pin!</p>";die;
        endif;
    
        wp_update_user( array( 'ID' => $uid, 'user_email' => $_POST['user_email'] ) );
    
        update_user_meta($uid, 'first_name', $_POST['first_name']);
    
        update_user_meta($uid, 'gender', strtoupper($_POST['gender']));
        update_user_meta($uid, 'dob', $_POST['dob']);
        //update_user_meta($uid, 'mobile_number', $_POST['mobile_number']);
        update_user_meta($uid, 'address1', $_POST['address1']);
        update_user_meta($uid, 'address2', $_POST['address2']);
        update_user_meta($uid, 'state', $_POST['state']);
        update_user_meta($uid, 'city', $_POST['city']);
        update_user_meta($uid, 'pin', $_POST['pin']);
        if ( ! empty( $_FILES['user_avatar']['name'] ) ) :
            isms_user_avater_upload($_FILES, $uid);
        endif;
        echo "<p class='box tick'>Account successfully updated!</p>";die;
    endif;
}


function fed_admin_to_user_mail_processing(){
    global $wpdb;
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'mail-send-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;
    
    if ( !$_POST['to']):
        echo "-1<p class='box alert'>Please enter user email!</p>";die;
    endif;
    
    if ( !$_POST['subject']):
        echo "-1<p class='box alert'>Please enter a message subject!</p>";die;
    endif;
    
    if ( !$_POST['message']):
        echo "-1<p class='box alert'>Please enter message description!</p>";die;
    endif;
    
    
    $first_name = $_POST['user_name']?$_POST['user_name']:"User";
    $mail = new RhMail();
    $email_body = $mail->common_email($first_name, $_POST['message']);
    $subject = "ISMS :: ".$_POST['subject'];
    $send_result = $mail->send_general_email( $email_body,  $_POST['to'], $subject);
    
    echo "<p class='box tick'>Message successfully send!</p>";die;
}
    
    
function fed_send_global_email_processing(){
        global $wpdb;
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'global-email-nonce' ) ):
            echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
        endif;
        
        if ( !$_POST['to']):
            echo "-1<p class='box alert'>Please select email group!</p>";die;
        endif;
        
        if ( !$_POST['subject']):
            echo "-1<p class='box alert'>Please enter a message subject!</p>";die;
        endif;
        
        if ( !$_POST['message']):
            echo "-1<p class='box alert'>Please enter message description!</p>";die;
        endif;
    
        $user_query = new WP_User_Query(
            array(
                'role' => $_POST['to']
            )
        );
    
        $mail = new RhMail();
        $subject = "ISMS :: ".$_POST['subject'];
    
        if ( ! empty( $user_query->get_results() ) ):
            $cnt = 0;
            foreach ($user_query->get_results() as $usr):
                if($usr->user_email):
                    $first_name = get_user_meta($usr->ID, 'first_name', true)?get_user_meta($usr->ID, 'first_name', true):$_POST['to'];
                    $email_body = $mail->common_email($first_name, $_POST['message']);
                    $send_result = $mail->send_general_email( $email_body,  $usr->user_email, $subject);
                endif;
            endforeach;
        endif;
        echo "<p class='box tick'>Message successfully send!</p>";die;
}


function fed_send_email_to_admin_processing(){
    global $wpdb;
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'user-email-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;
  
    if ( !$_POST['subject']):
        echo "-1<p class='box alert'>Please enter your question!</p>";die;
    endif;
    
    if ( !$_POST['message']):
        echo "-1<p class='box alert'>Please enter question description!</p>";die;
    endif;
    
    $userinfo = wp_get_current_user();
    
    $first_name = "Administrator";
    $mail = new RhMail();
    $main_message = "<strong>Q. </strong>: ".$_POST['subject']."<br><br>";
    $main_message .= "<strong>Description </strong>: ".$_POST['message'];
    $email_body = $mail->common_email($first_name, $main_message);
    $to = get_option( 'admin_email' );
    $subject = "ISMS - User Question by - ".$userinfo->user_login;
    $send_result = $mail->send_general_email( $email_body,  $to, $subject);
    
    echo "<p class='box tick'>Question successfully send to our support!</p>";die;
}


function fed_exchange_agent_processing(){
    global $wpdb;
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'agent-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;
    
    if ( !$_POST['from']):
        echo "-1<p class='box alert'>Please select FROM agent!</p>";die;
    endif;
    
    if ( !$_POST['to']):
        echo "-1<p class='box alert'>Please select TO agent!</p>";die;
    endif;
    
    if ( $_POST['from'] == $_POST['to']):
        echo "-1<p class='box alert'>FROM & TO agent should not be same!</p>";die;
    endif;
    
    $fuser = get_user_by('login', $_POST['from']);
    $tuser = get_user_by('login', $_POST['to']);
    
    $user_query = new WP_User_Query(
        array(
            'role' => 'Customer' ,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'agent_id',
                    'value'   => $_POST['from'],
                    'compare' => '='
                )
            )
        )
    );
    
    if ( ! empty( $user_query->get_results() ) ):
        $cnt = 0;
        foreach ($user_query->get_results() as $usr):
            update_user_meta($usr->ID, 'agent_id', $_POST['to']);
            $cnt++;
        endforeach;
    
        $reg_lead = get_user_meta($tuser->ID, "reg_lead", true)?get_user_meta($tuser->ID, "reg_lead", true):0;
        $new_reg_lead = $reg_lead + $cnt;
        update_user_meta($tuser->ID, 'reg_lead', $new_reg_lead);
    
        update_user_meta($fuser->ID, 'reg_lead',0);
        
        $f_target_lead = get_user_meta($fuser->ID, 'target_lead', true)?get_user_meta($fuser->ID, 'target_lead', true):0;
        
        $current_t_user_lead = get_user_meta($tuser->ID, 'target_lead', true)?get_user_meta($tuser->ID, 'target_lead', true):0;
        
        update_user_meta($tuser->ID, 'target_lead', ($f_target_lead+$current_t_user_lead));
        
        echo "<p class='box tick'>Successfully updated!</p>";die;
    endif;
    echo "-1<p class='box alert'>No customer found in FORM agent!</p>";die;
}


/**
 * AJAX: get all distributors update
 */
function fed_get_all_distributors_processing(){
    global $wpdb, $blog_id;


    // initilize all variable
    $params = $columns = $totalRecords = $data = array();

    $params = $_REQUEST;

    //define index of column
    $columns = array(
        1 => 'user_login',
        6 => 'user_status'
    );

    $where = $join = $groupby = $sqlTot = $sqlRec = "";

    $where = " WHERE 1=1 ";


    if( !empty($params['search']['value']) ):
        $join .= "  INNER JOIN wp_usermeta AS mt1 ON ( wp_users.ID = mt1.user_id ) ";
        $search_term = esc_attr($params['search']['value']);
        $where .= "AND ( 
                      ( 
                        ( 
                          ( wp_usermeta.meta_key = 'first_name' AND wp_usermeta.meta_value LIKE '%{$search_term}%' ) 
                          OR 
                          ( wp_usermeta.meta_key = 'dist_id' AND wp_usermeta.meta_value LIKE '%{$search_term}%' ) 
                          
                        ) 
                        OR 
                        ( 
                          ( 
                            (user_login LIKE '%{$search_term}%' OR user_email LIKE '%{$search_term}%')
                          )
                        )
                      )
                    ) AND ( mt1.meta_key = 'wp_capabilities' AND mt1.meta_value LIKE '%Distributor%' )";
    else:
         $where .= " AND ( 
                      ( 
                        ( wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%Distributor%' )
                      )
                    )";
    endif;

    if($params['columns'][6]['search']['value'] >= 1){

        if($params['columns'][6]['search']['value'] == 2) {
            $where .= " AND  user_status =  1  ";
        }

        if($params['columns'][6]['search']['value'] == 1) {
            $where .= " AND  user_status =  0 ";
        }


    }


    $args['number'] = $params['start'];
    $args['offset'] = $params['length'];

    $order_by = $columns[$params['order'][0]['column']]?$columns[$params['order'][0]['column']]:'ID';

    $sqlRec = "SELECT SQL_CALC_FOUND_ROWS wp_users.* FROM wp_users INNER JOIN wp_usermeta ON ( wp_users.ID = wp_usermeta.user_id ) {$join} {$where} GROUP BY wp_users.ID ORDER BY {$order_by} {$params['order'][0]['dir']} ";

    $sqlTot = $wpdb->get_results($sqlRec);
    $totalRecords = 0;
    if($sqlTot):
        $totalRecords = count($sqlTot);
    endif;

    $sqlRec = $sqlRec." LIMIT {$params['start']}, {$params['length']}";
    $queryRecords = $wpdb->get_results($sqlRec);


    $data = [];
    if ( $queryRecords ):
        $i = 1;
        foreach ($queryRecords as $user):
            $row = [];
            $row['row_id'] = $i;
            $row['user_login'] = $user->user_login;
            $row['pwd'] = encrypt_decrypt('decrypt', get_user_meta($user->ID, 'pwd', true));
            $row['first_name'] = get_user_meta($user->ID, 'first_name', true)?get_user_meta($user->ID, 'first_name', true):"--";
            $dist_id = get_user_meta($user->ID, 'dist_id', true);
            if($dist_id):
                $u = get_user_by('login', $dist_id);
                $row['dist_id'] =  get_user_meta($u->ID, 'first_name', true)?get_user_meta($u->ID, 'first_name', true)."<br>(ID: ".$u->user_login.")":"ID: ".$u->user_login;
            else:
                $row['dist_id'] =  "N/A";
            endif;
            $row['total_agent'] = isms_get_total_agents_by_distributor($user->ID);
            $row['lead_agent']  = isms_get_total_leads_by_agents_single_distributor($user->ID);
            $row['user_status'] = $user->user_status == 1?'<span class="label label-danger">De-Active</span>':'<span class="label label-success">Active</span>';

            $view_link = site_url("/dashboard/")."?menu_type=user&menu_slug=distributors&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=distview&rid=".encrypt_decrypt('encrypt',$user->ID);
            $edit_link = site_url("/dashboard/")."?menu_type=user&menu_slug=distributors&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=distedit&rid=".encrypt_decrypt('encrypt',$user->ID);
            $row['actions'] =  '<a class="btn btn-info btn-xs" data-action="user_view" href="'.$view_link.'" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn btn-warning btn-xs" data-action="user_edit" href="'.$edit_link.'" role="button" alt="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a class="btn btn-danger btn-xs js-request-delete" href="javascript://" role="button" alt="Delete" data-req="'.encrypt_decrypt('encrypt',$user->ID).'" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
            $data[] = $row;
            $i++;
        endforeach;
    endif;

    $json_data = array(
        "draw"            => intval( $params['draw'] ),
        "recordsTotal"    => intval( $totalRecords ),
        "recordsFiltered" => intval($totalRecords),
        "data"            => $data   // total data array
    );

    echo json_encode($json_data);  // send data as json format
    die;
}

function fed_distributor_data_update_processing(){
    global $wpdb;
    
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'distributor-update-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;
    
    $uid = encrypt_decrypt('decrypt', $_POST['id']);
    
    if ( !$_POST['first_name']):
        echo "-1<p class='box alert'>Please enter distributor name!</p>";die;
    endif;
    
    if ( !$_POST['user_email']):
        echo "-1<p class='box alert'>Please enter distributor email id!</p>";die;
    endif;
    
    if ( !is_email($_POST['user_email'])):
        echo "-1<p class='box alert'>Please enter distributor valid email id!</p>";die;
    endif;
    $user = get_user_by('email', $_POST['user_email']);
    
    if( $user && ($uid != $user->ID) ):
        echo "-1<p class='box alert'>Email id already taken, please enter different email!</p>";die;
    endif;
    
    if ( !$_POST['mobile_number']):
        echo "-1<p class='box alert'>Please enter distributor mobile number!</p>";die;
    endif;
    
    
    $user_data = get_user_by('ID', $uid);
    $pwd = encrypt_decrypt('decrypt', get_user_meta($uid, 'pwd', true));
    $new_pwd = $_POST['user_pass'];
    
    // create the wp hasher to add some salt to the md5 hash
    require_once( ABSPATH . '/wp-includes/class-phpass.php');
    $wp_hasher = new PasswordHash(8, TRUE);
    // check that provided password is correct
    $check_pwd = $wp_hasher->CheckPassword($new_pwd, $user_data->user_pass);
    
    if(!$check_pwd):
        wp_update_user( array( 'ID' => $uid, 'user_pass' => $new_pwd ) );
        update_user_meta($uid, 'pwd', encrypt_decrypt('encrypt', $new_pwd) );
    endif;
    
    wp_update_user( array( 'ID' => $uid, 'user_email' => $_POST['user_email'] ) );
    
    update_user_meta($uid, 'first_name', $_POST['first_name']);
    update_user_meta($uid, 'mobile_number', $_POST['mobile_number']);
    update_user_meta($uid, 'address1', $_POST['address1']);
    update_user_meta($uid, 'address2', $_POST['address2']);
    update_user_meta($uid, 'state', $_POST['state']);
    update_user_meta($uid, 'city', $_POST['city']);
    update_user_meta($uid, 'pin', $_POST['pin']);
    
    
    $wpdb->query("UPDATE `wp_users` SET user_status = {$_POST['user_status']} WHERE ID = '{$uid}';");
    
    if ( ! empty( $_FILES['user_avatar']['name'] ) ) :
        isms_user_avater_upload($_FILES, $uid);
    endif;
    echo "<p class='box tick'>Distributor successfully updated!</p>";die;
}


function fed_exchange_distributor_processing(){
    global $wpdb;
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'distributor-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;
    
    if ( !$_POST['from']):
        echo "-1<p class='box alert'>Please select FROM distributor!</p>";die;
    endif;
    
    if ( !$_POST['to']):
        echo "-1<p class='box alert'>Please select TO distributor!</p>";die;
    endif;
    
    if ( $_POST['from'] == $_POST['to']):
        echo "-1<p class='box alert'>FROM & TO distributor should not be same!</p>";die;
    endif;
    
    $fuser = get_user_by('login', $_POST['from']);
    $tuser = get_user_by('login', $_POST['to']);
    
    $user_query = new WP_User_Query(
        array(
            'role' => 'Agent' ,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'dist_id',
                    'value'   => $_POST['from'],
                    'compare' => '='
                )
            )
        )
    );
    
    if ( ! empty( $user_query->get_results() ) ):
        $cnt = 0;
        foreach ($user_query->get_results() as $usr):
            update_user_meta($usr->ID, 'dist_id', $_POST['to']);
            $cnt++;
        endforeach;
        echo "<p class='box tick'>Successfully updated!</p>";die;
    endif;
    echo "-1<p class='box alert'>No agent found in FORM distributor!</p>";die;
}