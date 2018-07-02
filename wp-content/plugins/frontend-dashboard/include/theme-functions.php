<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 30/5/18
 * Time: 9:36 PM
 */


add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

function wti_loginout_menu_link( $items, $args ) {

    if ($args->theme_location == 'primary-menu') {
        if (is_user_logged_in()) {
            $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1111"><a href="'. site_url('dashboard') .'">'. __("My Account") .'</a></li>';
            $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1112"><a href="'. wp_logout_url( home_url() ) .'">'. __("Log Out") .'</a></li>';
        } else {
            $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1113"><a href="'. site_url('sign-in') .'">'. __("Log In") .'</a></li>';
            //$items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1114"><a href="'. site_url('sign-up') .'">'. __("Sign Up") .'</a></li>';
        }
    }
    return $items;
}

