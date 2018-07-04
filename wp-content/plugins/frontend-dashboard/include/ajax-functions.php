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
        update_user_meta($userinfo->ID, 'pwd', $_POST['password']);
    endif;

    if(current_user_can('distributor')):
        update_user_meta($userinfo->ID, 'pwd', $_POST['password']);
    endif;

    if(current_user_can('customer')):
        update_user_meta($userinfo->ID, 'pwd', $_POST['password']);
    endif;

    $update_result = wp_update_user($user);
    if( ! is_wp_error( $update_result ) ) {
        echo "<p class='box tick'>Thank you! Your password has been successfully changed.</p>";die;
    } else {
        echo "-1<p class='box alert'>Some error happen, please try after later.</p>";die;
    }

    die;
}

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
            update_user_meta($user_id, 'target_lead', $_POST['leads_number']?$_POST['leads_number']:1);
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
                        update_user_meta($user_id, 'target_lead', $_POST['leads_number']?$_POST['leads_number']:1);
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

