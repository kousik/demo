<?php

add_action("fbd_assign_contractors", "fbd_custom_cron_job_processing", 10);


// Hook our class to the REST API.
add_action( 'rest_api_init', 'tls_register_rest_elink_routes', 20 );
/**
 * Register our routes with the REST API.
 *
 * @since 1.0.0
 */
function tls_register_rest_elink_routes() {
    $link_controller = new Fbd_API_Controller();
    $link_controller->register_routes();
}

/**
 * Class for handling Links in the REST API.
 *
 * @since 1.0.0
 */
class Fbd_API_Controller extends WP_REST_Controller
{
    /**
     * The base to use in the API route.
     *
     * @var string
     */
    protected $base = 'fbd';


    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {


        register_rest_route($this->base, '/everyhours', array(
            array(
                'methods' => 'GET',
                'callback' => array($this, 'fdb_every_hours_cron'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        ));
    }




    public function fdb_every_hours_cron(){

        $meeting_time = date('Y-m-d H:i:s', time() - 60 * 60 * 2);
        $meeting_time_less = date('Y-m-d H:i:s', time() - 60 * 60 * 24);

        $params = array(
            'where' => ' (t.created BETWEEN "'.$meeting_time_less.'" AND  "'.$meeting_time.'") ',
            'limit'   => -1  // Return all rows
        );

        $rf_requests = pods( 'roofhub_request', $params );

        error_log(print_r($rf_requests, true));


        return array(
            "status"  =>  1,
            "message" => "dfdfd"
        );
    }


    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
        if(!is_user_logged_in()):
            return [ 'status' => '-2', 'message' => 'Authentication required!' ];
        endif;
        return true;
    }
}


function fbd_custom_cron_job_processing(){
    global $wpdb;
    $url = site_url('/fbd/wp-json/everyhours');
    //httpGet($url);

    $meeting_time = date('Y-m-d H:i:s', time() - 60 * 60 * 2);
    $meeting_time_less = date('Y-m-d H:i:s', time() - 60 * 60 * 24);

    $params = array(
        'where' => ' (t.created BETWEEN "'.$meeting_time_less.'" AND  "'.$meeting_time.'") ',
        'limit'   => -1  // Return all rows
    );

    $rf_requests = pods( 'roofhub_request', $params );

    if ( 0 < $rf_requests->total() ):
        $i = 1;
        while ( $rf_requests->fetch() ):
            $req_id = $rf_requests->display('id');
            $pod = pods( 'roofhub_request', $req_id );

            if($rf_requests->display( 'assign_contractors')):

                $bc = $rf_requests->field( 'bidding_contractors' , null, true  );
                if($bc):
                    $pod->save( 'bidding_contractors', [] );

                    foreach($bc as $bkey => $b):
                        $params = array(
                            'where' => ' author.ID = '.$b['ID'].' ',
                            'limit'   => 1  // Return all rows
                        );
                        $bcon = pods( 'contractor', $params );

                        $edata = [];
                        $cid = '';
                        while ($bcon->fetch()):
                            $val = $bcon->field('assign_roofing_request', null, true);
                            if ($val):
                                foreach ($val as $vkey => $v):
                                    if($req_id != $v['id']):
                                        $edata[] = $v['id'];
                                    endif;
                                endforeach;
                                $bcon->save( ['assign_roofing_request' => $edata]);
                            endif;
                        endwhile;

                        $pod->save( 'status', 'Assign' );
                    endforeach;
                endif;

            elseif ($rf_requests->display( 'bidding_contractors') && !$rf_requests->display( 'assign_contractors')):

                $bc = $rf_requests->field( 'bidding_contractors' , null, true  );
                if($bc):
                    foreach($bc as $bkey => $b):
                        $params = array(
                            'where' => ' author.ID = '.$b['ID'].' ',
                            'limit'   => 1  // Return all rows
                        );
                        $bcon = pods( 'contractor', $params );

                        $edata = [];
                        while ($bcon->fetch()):
                            $val = $bcon->field('assign_roofing_request', null, true);
                            if ($val):
                                foreach ($val as $vkey => $v):
                                    if($req_id != $v['id']):
                                        $edata[] = $v['id'];
                                    endif;
                                endforeach;
                                $bcon->save( ['assign_roofing_request' => $edata]);
                            endif;
                        endwhile;

                        $pod->save( 'status', 'Request' );
                    endforeach;
                endif;

            else:
                $params = array(
                    'where' => ' (assign_roofing_request.ID = "'.$req_id.'") ',
                    'limit'   => -1  // Return all rows
                );
                $cons = pods( 'contractor', $params );
                if ( 0 < $cons->total() ):
                    while ($cons->fetch()):
                        $sc_id = $cons->display('id');
                        $scon = pods( 'contractor', $sc_id );
                        $val = $cons->field('assign_roofing_request', null, true);
                        if ($val):
                            $edata = [];
                            foreach ($val as $vkey => $v):
                                if($req_id != $v['id']):
                                    $edata[] = $v['id'];
                                endif;
                            endforeach;
                            $scon->save( ['assign_roofing_request' => $edata]);
                        endif;
                    endwhile;
                endif;




                $contractors = get_all_contractors($rf_requests->display('state'));
                if($contractors):
                    foreach($contractors as $conid):
                        $conpod = pods( 'contractor', $conid );

                        $as = $conpod->field( 'assign_roofing_request' , null, true  );
                        $ass_id = [];
                        if($as):
                            foreach($as as $akey => $a):
                                $ass_id[] =  $a['id'];
                            endforeach;
                            array_push($ass_id, $req_id);
                        else:
                            $ass_id = $req_id;
                        endif;
                        //error_log("sdsd".print_r($ass_id, true));

                        $res = $conpod->save( ['assign_roofing_request'=>$ass_id] );

                        $link = site_url("/dashboard/")."?menu_type=user&menu_slug=assigned_roofing_request&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=view&rid=".encrypt_decrypt('encrypt',$req_id);
                        $conuser = $conpod->field( 'author' , null, true  );
                        $mail = new RhMail();
                        $email_body = $mail->new_req_to_contractor_email($conpod->display('contact_name'), $link);
                        $subject = "RoofHub: New Roofing request inboxed";
                        $send_result = $mail->send_general_email( $email_body,  $conpod->display('email_address'), $subject );
                    endforeach;
                endif;

            endif;
        endwhile;
    endif;
}


function httpGet($url){
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept-Encoding:gzip'));

    $output=curl_exec($ch);

    curl_close($ch);
    return json_encode($output);
}