<?php
/**
 * Autoload Files
 */
require_once BC_FED_PLUGIN_DIR . '/admin/install/install.php';
require_once BC_FED_PLUGIN_DIR . '/admin/install/initial_setup.php';
/**
 * Loader
 */
require_once BC_FED_PLUGIN_DIR . '/include/loader/FED_Template_Loader.php';
require_once BC_FED_PLUGIN_DIR . '/include/page-template/FED_Page_Template.php';


/**
 * Include Necessary Files
 */
require_once BC_FED_PLUGIN_DIR . '/admin/menu/FED_AdminMenu.php';

require_once BC_FED_PLUGIN_DIR . '/admin/model/user_profile.php';
require_once BC_FED_PLUGIN_DIR . '/admin/model/menu.php';
require_once BC_FED_PLUGIN_DIR . '/admin/model/common.php';


require_once BC_FED_PLUGIN_DIR . '/admin/function-admin.php';

require_once BC_FED_PLUGIN_DIR . '/admin/request/menu.php';
require_once BC_FED_PLUGIN_DIR . '/admin/request/admin.php';
require_once BC_FED_PLUGIN_DIR . '/admin/request/function.php';
require_once BC_FED_PLUGIN_DIR . '/admin/request/user_profile.php';

require_once BC_FED_PLUGIN_DIR . '/admin/request/tabs/user_profile_layout.php';
require_once BC_FED_PLUGIN_DIR . '/admin/request/tabs/post_options.php';
require_once BC_FED_PLUGIN_DIR . '/admin/request/tabs/login.php';
require_once BC_FED_PLUGIN_DIR . '/admin/request/tabs/user.php';

require_once BC_FED_PLUGIN_DIR . '/common/function-common.php';
require_once BC_FED_PLUGIN_DIR . '/common/script.php';

/**
 * Shortcodes | Login
 */
require_once BC_FED_PLUGIN_DIR . '/shortcodes/login/login-shortcode.php';
require_once BC_FED_PLUGIN_DIR . '/shortcodes/login/login-data.php';
require_once BC_FED_PLUGIN_DIR . '/shortcodes/login/login-only-shortcode.php';
require_once BC_FED_PLUGIN_DIR . '/shortcodes/login/register-only-shortcode.php';
require_once BC_FED_PLUGIN_DIR . '/shortcodes/login/forgot-password-only-shortcode.php';

require_once BC_FED_PLUGIN_DIR . '/shortcodes/user_role.php';

require_once BC_FED_PLUGIN_DIR . '/shortcodes/dashboard/dashboard-shortcode.php';

require_once BC_FED_PLUGIN_DIR . '/frontend/menu/menus.php';

require_once BC_FED_PLUGIN_DIR . '/frontend/request/login/login.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/request/login/index.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/request/login/forgot.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/request/login/reset.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/request/login/register.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/request/login/validation.php';

require_once BC_FED_PLUGIN_DIR . '/frontend/request/support/support.php';

require_once BC_FED_PLUGIN_DIR . '/frontend/request/dashboard/post.php';

require_once BC_FED_PLUGIN_DIR . '/frontend/controller/profile.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/controller/menu.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/controller/payment.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/controller/posts.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/controller/support.php';
require_once BC_FED_PLUGIN_DIR . '/frontend/controller/logout.php';

require_once BC_FED_PLUGIN_DIR . '/frontend/request/user_profile/user_profile.php';

require_once BC_FED_PLUGIN_DIR . '/frontend/request/validation/validation.php';

require_once BC_FED_PLUGIN_DIR . '/frontend/function-frontend.php';

require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/checkbox.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/email.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/number.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/password.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/radio.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/select.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/text.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/textarea.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/url.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/input_fields/common.php';

require_once BC_FED_PLUGIN_DIR . '/admin/layout/FED_AdminUserProfile.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/add_edit_profile.php';


require_once BC_FED_PLUGIN_DIR . '/admin/layout/metabox/post-meta-box.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/error.php';

require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/user_profile/user_profile_tab.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/user_profile/settings.php';

require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/user/user_tab.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/user/role.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/user/user_upload.php';

require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/post/permissions.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/post/dashboard.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/post/post_tab.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/post/settings.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/post/menu.php';

require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/login/login_tab.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/login/register_tab.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/login/settings.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/settings_tab/login/restrict_wp_tab.php';

require_once BC_FED_PLUGIN_DIR . '/admin/layout/custom_layout/FEDCustomCSS.php';
require_once BC_FED_PLUGIN_DIR . '/admin/layout/custom_layout/helper.php';

require_once BC_FED_PLUGIN_DIR . '/config/config.php';

require_once BC_FED_PLUGIN_DIR . '/route/FED_Routes.php';

require_once BC_FED_PLUGIN_DIR . '/admin/hooks/FED_ActionHooks.php';


add_action("init", "fad_ajax_hook_check", 12);
function fad_ajax_hook_check(){
    global $epic;
    if( !isset($_REQUEST['fed_ajax_hook']) || !$_REQUEST['fed_ajax_hook'] )
        return true;

    if( isset($_REQUEST['fed_ajax_hook']) &&  ($epic->uri[0] == "wp-load.php" || isset($_REQUEST['_wpnonce'])) ):
        $sanitized_hook = epic_data_escape($_REQUEST['fed_ajax_hook'], "strip");
        do_action("fed_".$sanitized_hook."_processing");
    endif;
}


/*enqueue styles & javascript files for use in the community extension*/
add_action("wp_enqueue_scripts", "epic_community_scripts", 9);
function epic_community_scripts(){
    global $post, $epic, $wp_rewrite;

    /*JAVASCRIPT*/
    //wp_enqueue_script( 'recurly', 'https://js.recurly.com/v4/recurly.js', array( 'jquery' ) );
    wp_enqueue_script( 'community-form', plugins_url('/_inc/tparty/forms/jquery.form.js', BC_FED_PLUGIN ), array( 'jquery' ), BC_FED_PLUGIN_VERSION, false );
    wp_enqueue_script( 'datatable-js', plugins_url('/_inc/tparty/DataTables/datatables.min.js', BC_FED_PLUGIN ), array( 'jquery' ), BC_FED_PLUGIN_VERSION, false );


    wp_enqueue_script( 'epic-community-js', plugins_url('/_inc/js/community.js', BC_FED_PLUGIN ), array( 'jquery' ), BC_FED_PLUGIN_VERSION, true );

    wp_enqueue_script( 'easing-js', plugins_url('/_inc/js/jquery.easing.min.js', BC_FED_PLUGIN ), array( 'jquery' ), BC_FED_PLUGIN_VERSION, false );

    //wp_enqueue_script( 'bootstrap-js', plugins_url('/_inc/tparty/bootstrap/js/bootstrap.min.js', BC_FED_PLUGIN ), array( 'jquery' ), BC_FED_PLUGIN_VERSION, false );

    wp_enqueue_script( 'chosen-js', plugins_url('/_inc/tparty/chosen/chosen.jquery.js', BC_FED_PLUGIN ), array( 'jquery' ), BC_FED_PLUGIN_VERSION, true );

    //wp_enqueue_script( 'fvalidator-js', plugins_url('/_inc/tparty/form-validator/dist/jquery.validate.js', BC_FED_PLUGIN ), array( 'jquery' ), BC_FED_PLUGIN_VERSION, false );

    //wp_enqueue_script( 'modal-js', plugins_url('/_inc/tparty/modal/jquery.modal.js', BC_FED_PLUGIN ), array( 'jquery' ), BC_FED_PLUGIN_VERSION, false );





    //CSS
    wp_register_style('prefix_bootstrap', plugins_url('/_inc/tparty/bootstrap/css/bootstrap.min.css', BC_FED_PLUGIN ) );
    wp_register_style('font-awesome', plugins_url("/_inc/tparty/font-awesome/css/font-awesome.min.css", BC_FED_PLUGIN ));
    wp_enqueue_style('prefix_bootstrap');
    wp_enqueue_style('font-awesome');


    wp_enqueue_style( 'epic-community-css', plugins_url('/_inc/css/community.css', BC_FED_PLUGIN ), all );
    wp_enqueue_style( 'chosen-css', plugins_url('/_inc/tparty/chosen/chosen.css', BC_FED_PLUGIN ), all );
    //wp_enqueue_style( 'modal-css', plugins_url('/_inc/tparty/modal/jquery.modal.css', BC_FED_PLUGIN ), all );
    wp_enqueue_style( 'modal-css', plugins_url('/_inc/tparty/DataTables/datatables.css', BC_FED_PLUGIN ), all );



}

add_action('wp_head', 'epic_community_custom_jsvariables', 20);
function epic_community_custom_jsvariables(){
   ?>
    <script type="text/javascript">
        var etajaxurl = "<?php echo site_url( 'wp-load.php' ); ?>";
    </script>
    <?php
    if ( ! current_user_can( 'administrator' ) ) :
    ?>
    <style>
        .admin-bar .site-header {
            top: 0px !important;
        }
    </style>
    <?php
        endif;
}

add_action('init', 'fdb_community_init', 1);
function fdb_community_init(){
    if(!isset($_COOKIE['state'])):
        $state = ip_info("Visitor", "State");
        $states = fdb_get_state($country_code = 101);
        $statArray = [];
        foreach($states as $key => $s):
            $statArray[] = $s->name;
        endforeach;
        if(!in_array($state, $statArray)):
            $state = "Alabama";
        endif;
        setcookie("state", $state,time()+86400);

    endif;
}

function kv_date_time_js() {
    //jQuery UI date picker file
    wp_enqueue_script('jquery-ui-datepicker');
    //wp_enqueue_script('jquery-ui-script', 'http://code.jquery.com/ui/1.11.0/jquery-ui.min.js', array('jquery' ), BC_FED_PLUGIN_VERSION, false);
    wp_enqueue_script('jquery-ui-core');
//jQuery UI theme css file
    wp_enqueue_style('e2b-admin-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);

    wp_register_style('kv_js_time_style' , plugins_url('_inc/tparty/datetime/jquery-ui-timepicker-addon.css', BC_FED_PLUGIN ));
    wp_enqueue_style('kv_js_time_style');
   // wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
    //wp_enqueue_script('jquery-script', 'http://code.jquery.com/ui/1.10.4/jquery-ui.js');

    wp_enqueue_script('jquery-time-picker' ,  plugins_url('_inc/tparty/datetime/jquery-ui-timepicker-addon.js', BC_FED_PLUGIN ),  array('jquery' ), BC_FED_PLUGIN_VERSION, true);

}
add_action('wp_enqueue_scripts', 'kv_date_time_js', 9);