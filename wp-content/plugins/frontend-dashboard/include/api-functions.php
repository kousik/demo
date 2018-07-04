<?php

if ( !class_exists( 'ismsMobAPI' ) ) :

    class ismsMobAPI {

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


        public function get_user_data_id($user_id){
            $user_info = get_userdata($user_id);
            $user = $user_info;
            $role = "";
            $udata = [];
            if(array_key_exists('agent', $user_info->wp_capabilities)):
                $role = 'agent';
                $udata['role'] = $role;
                $udata['role'] = get_user_meta($user->ID, 'target_lead', true)?get_user_meta($user->ID, 'target_lead', true):0;
                $udata['target_lead'] = get_user_meta($user->ID, 'target_lead', true)?get_user_meta($user->ID, 'target_lead', true):0;
                $udata['reg_lead'] = get_user_meta($user->ID, 'reg_lead', true)?get_user_meta($user->ID, 'reg_lead', true):0;
                $udata['role'] = get_user_meta($user->ID, 'target_start', true)?get_user_meta($user->ID, 'target_start', true):0;
                $udata['role'] = get_user_meta($user->ID, 'target_end', true)?get_user_meta($user->ID, 'target_end', true):0;
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


    }

endif;
