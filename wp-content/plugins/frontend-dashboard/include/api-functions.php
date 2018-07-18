<?php

if ( !class_exists( 'ismsMobAPI' ) ) :

    class ismsMobAPI {

        var $api_url;
        var $api_key = "9cc74e44-ec29-11e6-8c71-00163ef91450";


        /**
         * Class construct function
         **/
        function __construct(){
            global $epic;

            $this->api_url = "http://2factor.in/API/V1/".$this->api_key."/";
            $this->otp_tpl = "GLOBALOTP";
        }
        /**
         * @param $data
         * @return array
         */
        public function ismas_app_signup($data){
            global $epic, $blog_id, $wpdb;

            $user_login = epic_data_escape($data["user_login"]);
            $user_password = epic_data_escape($data["password"]);
            $user_email = epic_data_escape($data["user_login"]);


            if (username_exists($user_login) || email_exists($user_email)):

                $user_data = get_user_by('login', $user_login);

                if (!$user_data):
                    $user_data = get_user_by('email', $user_email);
                endif;

                if ( !wp_check_password( $user_password, $user_data->data->user_pass, $user_data->ID) ):
                    return [ 'status' => -1, 'error' => 'Invalid Password!' ];
                endif;
                $user_login = $user_data->user_login;

                // create the wp hasher to add some salt to the md5 hash
                require_once( ABSPATH . '/wp-includes/class-phpass.php');
                $wp_hasher = new PasswordHash(8, TRUE);
                // check that provided password is correct
                $check_pwd = $wp_hasher->CheckPassword($user_password, $user_data->user_pass);

                // if password is username + password are correct
                // signon with wordpress function and redirect wherever you want
                if ($check_pwd):

                    if($user_data->user_status):
                        return [ 'status' => -1, 'error' => 'Account suspended. Please contact our support!' ];
                    endif;
                    // Inserting new user to the db
                    $remember = (isset($data["rememberme"]) && $data["rememberme"]) ? true : false;
                    $creds = array();
                    $creds['user_login'] = $user_login;
                    $creds['user_password'] = $user_password;
                    $creds['remember'] = $remember;

                    $user = wp_signon($creds, true);
                    if (is_wp_error($user)):
                        return [ 'status' => -1, 'error' => 'Invalid Password!' ];
                    endif;

                    $udata = $this->get_user_data_id($user->ID);

                    return [ 'status' => 0, 'data' => $udata, 'userdata' => $user, 'msg' => "Successfully logged in!" ];
                else:
                    return [ 'status' => -1, 'error' => 'Username or password is incorrect. Please verify & try again.' ];
                endif;
            else:
                return [ 'status' => -1, 'error' => 'Username or password is incorrect. Please verify & try again.' ];
            endif;
        }


        public function ismas_get_userinfo($user_login){
            global $epic, $blog_id, $wpdb;

            $user_login = epic_data_escape($user_login);
            $user_password = epic_data_escape($user_login);
            $user_email = epic_data_escape($user_login);


            if (username_exists($user_login) || email_exists($user_email)):

                $user_data = get_user_by('login', $user_login);

                if (!$user_data):
                    $user_data = get_user_by('email', $user_email);
                endif;
                $udata = $this->get_user_data_id($user_data->ID);

                return [ 'status' => 0, 'data' => $udata ];
            else:
                return [ 'status' => -1, 'error' => 'User ID is incorrect. Please verify & try again.' ];

            endif;
        }



        public function get_user_data_id($user_id){
            $user_info = get_userdata($user_id);
            $user = $user_info;
            $role = "";
            $udata = [];
            if(array_key_exists('agent', $user_info->wp_capabilities)):
                $role = 'agent';
                $udata['role'] = $role;
                $udata['target_lead'] = get_user_meta($user->ID, 'target_lead', true)?get_user_meta($user->ID, 'target_lead', true):0;
                $udata['reg_lead'] = get_user_meta($user->ID, 'reg_lead', true)?get_user_meta($user->ID, 'reg_lead', true):0;
                $udata['target_start'] = get_user_meta($user->ID, 'target_start', true)?get_user_meta($user->ID, 'target_start', true):false;
                $udata['target_end'] = get_user_meta($user->ID, 'target_end', true)?get_user_meta($user->ID, 'target_end', true):false;
            endif;
            if(array_key_exists('distributor', $user_info->wp_capabilities)):
                $role = 'distributor';
                $udata['role'] = $role;
                $udata['total_agents'] = isms_get_total_agents_by_distributor($user->ID);
                $udata['total_agent_leads'] = isms_get_total_leads_by_agents_single_distributor($user->ID);
            endif;
            if(array_key_exists('customer', $user_info->wp_capabilities)):
                $role = 'customer';
                $udata['role'] = $role;
            endif;
            if(array_key_exists('administrator', $user_info->wp_capabilities)):
                $role = 'administrator';
                $udata['role'] = $role;
            endif;


            $udata['name'] = get_user_meta($user->ID, 'first_name', true);
            $udata['state'] = get_user_meta($user->ID, 'state', true);
            $udata['city'] = get_user_meta($user->ID, 'city', true);
            $udata['address'] = get_user_meta($user->ID, 'address', true);
            $udata['pin_code'] = get_user_meta($user->ID, 'pin_code', true);
            $udata['mobile_number'] = get_user_meta($user->ID, 'mobile_number', true);

            return $udata;
        }


        public function ismas_send_otp($phone_number){
            $mob_verify = $this->sendOtp($phone_number);
            if(isset($mob_verify['error'])):
                return [ 'status' => -1, 'error' => json_decode($mob_verify['error']) ];
            else:
                return [ 'status' => 0, 'data' => json_decode($mob_verify['response']) ];
            endif;
            /*if(!$phone_number):
                return [ 'status' => -1, 'error' => 'error' ];
            else:
                return [ 'status' => 0, 'data' => ['data'=>['Details'=> '12345678']] ];
            endif;*/
        }


        public function ismas_verify_otp($data){
            $verify_id = $data['verify_id'];
            $input_otp = $data['otp'];

            $mob_verify = $this->verifyOtp($verify_id, $input_otp);
            if(isset($mob_verify['error'])):
                return [ 'status' => -1, 'error' => json_decode($mob_verify['error']) ];
            else:
                $resp = json_decode($mob_verify['response']);
                if($resp->Status == "Error"):
                    return [ 'status' => -1, 'error' => $resp->Details ];
                else:
                    $pos = strpos($resp->Details, 'Expired');
                    if($pos !== false):
                        return [ 'status' => -1, 'error' => $resp->Details ];
                    else:
                        return [ 'status' => 0, 'data' => json_decode($mob_verify['response']) ];
                    endif;
                endif;
            endif;

            /*if($verify_id != '12345678'):
                return [ 'status' => -1, 'error' => 'error' ];
            else:
                return [ 'status' => 0, 'data' => ['data'=>['Details'=> 'Success']] ];
            endif;*/
        }


        public function ismas_app_signin($post){

            if($post['user_type'] == "customer"):
                if(!isset($post['mobile_number']) || !$post['mobile_number']):
                    return [ 'status' => -1, 'error' => 'Mobile number invalid!' ];
                endif;

                $user_name = isms_gen_cust_id($len = 12);
                $user_email = $post['user_email'];
                $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                $user_id = wp_create_user( $user_name, $random_password, $user_email );
                if( !is_wp_error( $user_id ) ):
                    return [ 'status' => -1, 'error' => 'Invalid data submission!' ];
                endif;

                $u = new WP_User( $user_id );
                $u->set_role( 'customer' );

                update_user_meta($user_id, 'pwd', $random_password);
                update_user_meta($user_id, 'agent_id', $post['agent_id']);
                update_user_meta($user_id, 'mobile_number', $post['mobile_number']);
                update_user_meta($user_id, 'address1', $post['address1']);
                update_user_meta($user_id, 'address2', $post['address2']);
                update_user_meta($user_id, 'state', $post['state']);
                update_user_meta($user_id, 'city', $post['city']);
                update_user_meta($user_id, 'pin', $post['pin']);

                update_user_meta($user_id, 'iemi', $post['iemi']);
                update_user_meta($user_id, 'make', $post['make']);
                update_user_meta($user_id, 'model', $post['model']);
                update_user_meta($user_id, 'adddr_ui', encrypt_decrypt('encrypt', $post['adddr_ui']) );
                update_user_meta($user_id, 'check_term', true);

                $agent = get_user_by("login", $post['agent_id']);
                $reg_lead = get_user_meta($agent->ID, "reg_lead", true)?get_user_meta($agent->ID, "reg_lead", true):0;
                $new_reg_lead = $reg_lead + 1;
                update_user_meta($agent->ID, 'reg_lead', $new_reg_lead);

            endif;


        }

        /**
         * @param $phone_number
         * @return mixed|string
         */
        public function sendOtp($phone_number){
            $url = $this->api_url.'SMS/'.$phone_number.'/AUTOGEN/'.$this->otp_tpl;
            //error_log('infodata: '.$url);
            $data = $this->myCurl($url);
            //error_log('data: '.print_r($data,true));
            return $data;
        }

        /**
         * @param $verify_id
         * @param $input_otp
         * @return mixed|string
         */
        public function verifyOtp($verify_id, $input_otp){
            $url = $this->api_url.'SMS/VERIFY/'.$verify_id.'/'.$input_otp;
            $data = $this->myCurl($url);
            return $data;
        }


        /**
         * @param $url
         * @param array $params
         * @return mixed|string
         */
        private function myCurl($url, $params=[]){
            $data = [];
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "{}",
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            //error_log('err: '.$err);
            //error_log('response: '.$response);
            if ($err) :
                $data['error'] = $err;
            else :
                $data['response'] = $response;
            endif;
            return $data;
        }

        /**
         * @param $url
         * @param array $params
         * @return mixed|string
         */
        private function myPostCurl($url, $params){
            $data = [];
            $curl = curl_init();
            /*foreach($params as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }

            rtrim($fields_string, '&');*/

            $data_string = json_encode($params);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POST => count($params),
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            //error_log('err: '.$err);
            //error_log('response: '.$response);
            if ($err) :
                $data['error'] = $err;
            else :
                $data['response'] = $response;
            endif;
            return $data;
        }



    }

endif;
