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


    $update_result = wp_update_user($user);
    if( ! is_wp_error( $update_result ) ) {
        echo "<p class='box tick'>Thank you! Your password has been successfully changed.</p>";die;
    } else {
        echo "-1<p class='box alert'>Some error happen, please try after later.</p>";die;
    }

    die;
}

