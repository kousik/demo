<?php
/**
 * Plugin Name: Roofhub PayPal Pro
 * Description: Easily adds PayPal Pro payment gateway to the WooCommerce plugin so you can allow customers to checkout via credit card.
 * Version: 2.3
 * Requires at least: 3.0
 * License: GPL2 or Later
 */

if (!defined('ABSPATH')) {
    //Exit if accessed directly
    exit;
}

//Slug - wcpprog

if (!class_exists('WC_Paypal_Pro_Gateway_Addon')) {

    class WC_Paypal_Pro_Gateway_Addon {

        var $version = '2.3';
        var $db_version = '1.0';
        var $plugin_url;
        var $plugin_path;

        function __construct() {
            $this->define_constants();
            $this->includes();
            $this->loader_operations();
        }

        function define_constants() {
            define('WC_PP_PRO_ADDON_VERSION', $this->version);
            define('WC_PP_PRO_ADDON_URL', $this->plugin_url());
            define('WC_PP_PRO_ADDON_PATH', $this->plugin_path());
        }

        function includes() {
            include_once('woo-paypal-pro-utility-class.php');
        }

        function loader_operations() {
            add_action('plugins_loaded', array(&$this, 'plugins_loaded_handler')); //plugins loaded hook		
        }

        function plugins_loaded_handler() {
            //Runs when plugins_loaded action gets fired
            include_once('woo-paypal-pro-gateway-class.php');
        }

        function do_db_upgrade_check() {
            //NOP
        }

        function plugin_url() {
            if ($this->plugin_url)
                return $this->plugin_url;
            return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
        }

        function plugin_path() {
            if ($this->plugin_path)
                return $this->plugin_path;
            return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
        }


    }

    //End of plugin class
}//End of class not exists check

 new WC_Paypal_Pro_Gateway_Addon();

