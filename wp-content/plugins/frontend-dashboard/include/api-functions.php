<?php

if ( !class_exists( 'ismsMobAPI' ) ) :

    class ismsMobAPI {

        /**
         * @param $data
         * @return array
         */
        public function ismas_app_signup($data){
            global $epic, $blog_id, $wpdb;

            return [ 'status' => 0, 'data' => ['role'=> 'agent'], 'msg' => "Successfully logged in!" ];

           /* if(!$data['url']):
                return ["status" => -1, "error" => "Invalid URL!"];
            endif;


            if(true):
                //set_transient($sql_hash, $response, 24 * HOUR_IN_SECONDS);
                return [ 'status' => 0, 'data' => ["response" => json_decode($response)] ];
            else:
                return [ 'status' => -1, 'error' => 'Invalid or insufficient data' ];
            endif;*/
        }


    }

endif;
