<?php
// Hook our class to the REST API.
add_action( 'rest_api_init', 'isms_register_rest_routes', 100 );
/**
 * Register our routes with the REST API.
 *
 * @since 1.0.0
 */
function isms_register_rest_routes() {
    $link_controller = new Isms_API_Controller();
    $link_controller->register_routes();
}

/**
 * Class for handling Links in the REST API.
 *
 * @since 1.0.0
 */
class Isms_API_Controller extends WP_REST_Controller
{
    /**
     * The base to use in the API route.
     *
     * @var string
     */
    protected $base = 'isms';


    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {

        /**
         * E-Link API Endpoints
         */
        register_rest_route($this->base, '/app/signup', array(
            array(
                'methods' => 'POST',
                'callback' => array($this, 'ismas_app_signup'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));

        register_rest_route( $this->base, '/app/user/(?P<id>[a-zA-Z0-9-]+)', array(
            array(
                'methods' => 'GET',
                'callback' => array( $this, 'ismas_get_userinfo' ),
                'args' => [
                    'id'
                ],
            )
        ));

        register_rest_route( $this->base, '/app/user/send/otp/(?P<mobile>[a-zA-Z0-9-]+)', array(
            array(
                'methods' => 'GET',
                'callback' => array( $this, 'ismas_send_otp' ),
                'args' => [
                    'id'
                ],
            )
        ));

        register_rest_route($this->base, '/app/user/verify/otp', array(
            array(
                'methods' => 'POST',
                'callback' => array($this, 'ismas_verify_otp'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));


        register_rest_route($this->base, '/app/signin', array(
            array(
                'methods' => 'POST',
                'callback' => array($this, 'ismas_app_signin'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));

        register_rest_route($this->base, '/app/upload', array(
            array(
                'methods' => 'POST',
                'callback' => array($this, 'ismas_app_image_upload'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));
    
    
        register_rest_route($this->base, '/app/extract/uuid', array(
            array(
                'methods' => 'POST',
                'callback' => array($this, 'ismas_app_extract_uuid'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));
    
        register_rest_route($this->base, '/app/validate', array(
            array(
                'methods' => 'POST',
                'callback' => array($this, 'ismas_app_validate'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));


        /*register_rest_route($this->base, '/app/signin', array(
            array(
                'methods' => 'POST',
                'callback' => array($this, 'epic_elink_request_url_data'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));*/


        /*register_rest_route($this->base, '/elink/getlink', array(
            array(
                'methods' => 'GET',
                'callback' => array($this, 'epic_elink_get_link'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));


        register_rest_route( $this->base, '/epic_unsubscribe_alert', array(
            array(
                'methods' => 'PUT',
                'callback' => array( $this, 'epic_unsubscribe_endpoint' ),

            )
        ));


        register_rest_route( $this->base, '/customer/(?P<id>\w+)', array(
            array(
                'methods' => 'GET',
                'callback' => array( $this, 'epic_get_stream' ),

            ),
            array(
                'methods' => 'PUT',
                'callback' => array( $this, 'epic_update_stream' ),

            ),
            array(
                'methods' => 'DELETE',
                'callback' => array( $this, 'epic_delete_stream' ),

            )
        ));*/
    }

    /**
     * ISMS API Endpoints
     */
    public function ismas_app_signup($request){
        if ( class_exists( 'ismsMobAPI' ) ) :
            $myApi = new ismsMobAPI();
            return $myApi->ismas_app_signup($request->get_json_params());
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }

    public function ismas_get_userinfo($request){
        if ( class_exists( 'ismsMobAPI' ) ) :
            $myApi = new ismsMobAPI();
            return $myApi->ismas_get_userinfo($request['id']);
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }

    public function ismas_send_otp($request){
        if ( class_exists( 'ismsMobAPI' ) ) :
            $myApi = new ismsMobAPI();
            return $myApi->ismas_send_otp($request['mobile']);
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }

    public function ismas_verify_otp($request){
        if ( class_exists( 'ismsMobAPI' ) ) :
            $myApi = new ismsMobAPI();
            return $myApi->ismas_verify_otp($request->get_json_params());
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }


    public function ismas_app_signin($request){
        if ( class_exists( 'ismsMobAPI' ) ) :
            $myApi = new ismsMobAPI();
            return $myApi->ismas_app_signin($request->get_json_params());
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }


    public function ismas_app_image_upload($request){
        if ( class_exists( 'ismsMobAPI' ) ) :
            $myApi = new ismsMobAPI();
            return $myApi->ismas_app_image_upload($request->get_json_params());
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }


    public function ismas_app_extract_uuid($request){
        if ( class_exists( 'ismsMobAPI' ) ) :
            $myApi = new ismsMobAPI();
            return $myApi->ismas_app_extract_uuid($request->get_json_params());
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }
    
    public function ismas_app_validate($request){
        if ( class_exists( 'ismsMobAPI' ) ) :
            $myApi = new ismsMobAPI();
            return $myApi->ismas_app_validate($request->get_json_params());
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }

    /*public function epic_elink_add_on_es_server($request){
        if ( class_exists( 'epictionsElinkAPI' ) ) :
            $myApi = new epictionsElinkAPI();
            return $myApi->epic_elink_add_on_es_server($request->get_json_params());
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }


    public function epic_elink_get_link(){
        if ( class_exists( 'epictionsElinkAPI' ) ) :
            $myApi = new epictionsElinkAPI();
            return $myApi->epic_elink_get_elink();
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }

    public function epic_elink_approved_server($request){
        if ( class_exists( 'epictionsElinkAPI' ) ) :
            $myApi = new epictionsElinkAPI();
            return $myApi->epic_elink_approved_server($request->get_json_params());
        endif;
        return array(
            "status"  => - 1,
            "message" => "No route was found matching the URL and request method"
        );
    }*/

    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
        //if(!is_user_logged_in()):
            //return [ 'status' => '-2', 'message' => 'Authentication required!' ];
        //endif;
        return true;
    }
}


