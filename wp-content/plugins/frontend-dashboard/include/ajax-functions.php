<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 16/5/18
 * Time: 1:05 AM
 */

//Actions

add_action('fed_global_data_update_processing', 'fed_global_data_update_for_pod', 12);
add_action('fed_delete_contractor_processing', 'fed_delete_contractor_processing', 20);
add_action('fed_delete_ref_by_id_processing', 'fed_delete_ref_by_id_processing', 10);
add_action('fed_delete_request_processing', 'fed_delete_request_processing', 20);
add_action('fed_update_user_ad_processing', 'fed_update_user_ad_processing', 20);
add_action('fed_delete_ad_processing', 'fed_delete_ad_processing', 20);
add_action('fed_make_payment_processing', 'fed_make_payment_processing', 20);
add_action('fed_add_user_ad_processing', 'fed_add_user_ad_processing', 20);

add_action('fed_update_email_processing', 'fed_update_email_processing', 20);
add_action('fed_update_password_processing', 'fed_update_password_processing', 20);

add_action('fed_get_ad_packeges_processing', 'fed_get_ad_packeges_processing', 10);

add_action('fed_get_ad_location_processing', 'fed_get_ad_location_processing', 10);

add_action('fed_assign_rf_req_to_contract_processing', 'fed_assign_rf_req_to_contract_processing', 10);

add_action('fed_submit_bid_comment_processing', 'fed_submit_bid_comment_processing', 10);

add_action('fed_offer_accept_processing', 'fed_offer_accept_processing', 10);
//Functions


/**
 * AJAX: Delete Roofing Request
 */
function fed_delete_request_processing(){
    global $wpdb;
    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    // Get the book item with an ID of 5
    $pod = pods( 'roofhub_request', $id );

    // Delete the current pod item
    $res = $pod->delete();
    $result = [];
    if($res):
        $result['msg'] = '<p class="box tick">Roof Request hes been deleted successfully!</p>';
        $result['delete'] = true;
    else:
        $result['msg'] = '<p class="box alert">Some error happen. Please try again later!</p>';
        $result['delete'] = false;
    endif;
    echo json_encode($result);die;
}


/**
 * Ajax: Delete Contractor Application
 */
function fed_delete_contractor_processing(){
    global $wpdb;
    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    // Get the book item with an ID of 5
    $pod = pods( 'contractor', $id );

    // Delete the current pod item
    $res = $pod->delete();
    $result = [];
    if($res):
        $result['msg'] = '<p class="box tick">Contractor Request hes been deleted successfully!</p>';
        $result['delete'] = true;
    else:
        $result['msg'] = '<p class="box alert">Some error happen. Please try again later!</p>';
        $result['delete'] = false;
    endif;
    echo json_encode($result);die;
}

/**
 * Ajax: Delete Contractor ref
 */
function fed_delete_ref_by_id_processing(){
    global $wpdb;
    $id = $_POST['id'];
    // Get the book item with an ID of 5
    $pod = pods( 'referral_information', $id );

    // Delete the current pod item
    $res = $pod->delete();
    $result = [];
    if($res):
        $result['msg'] = '<p class="box tick">Contractor Request hes been deleted successfully!</p>';
        $result['delete'] = true;
    else:
        $result['msg'] = '<p class="box alert">Some error happen. Please try again later!</p>';
        $result['delete'] = false;
    endif;
    echo json_encode($result);die;
}


/**
 * Ajax: Delete Advertisement Application
 */
function fed_delete_ad_processing(){
    global $wpdb;
    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    // Get the book item with an ID of 5
    $pod = pods( 'advertisement', $id );

    // Delete the current pod item
    //$res = $pod->delete();
    $res = $pod->save( 'is_delete', 1 );
    $result = [];
    if($res):
        $result['msg'] = '<p class="box tick">Advertisement hes been deleted successfully!</p>';
        $result['delete'] = true;
    else:
        $result['msg'] = '<p class="box alert">Some error happen. Please try again later!</p>';
        $result['delete'] = false;
    endif;
    echo json_encode($result);die;
}


/**
 * AJAX: Global pods data modify
 */
function fed_global_data_update_for_pod(){
    global $wpdb;
    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    $pod_name = $_POST['type'];
    // Get the book item with an ID of 5
    $pod = pods( $pod_name, $id );
    if($pod_name == "contractor"):
        $prev_status = $pod->field( 'status', null, true );
    endif;

    $data = $_POST;
    unset($data['id']);
    unset($data['type']);
    unset($data['_wpnonce']);
    unset($data['_wp_http_referer']);
    unset($data['noreset']);
    unset($data['redirect']);


    if($pod_name != "contractor" && $pod_name != "roofhub_request"):
        $item_id = $pod->save( $data );
    endif;

    if($pod_name == "roofhub_request"):
        if ( ! $_POST['status'] ):
            echo "-1<p class='box alert'>Please select request status!</p>";die;
        endif;

        if($data['assign_contractors']):
            //$data['bidding_contractors'] = [];
        endif;

        if($data['assign_contractors']):
            $data['status'] = 'Assign';
        endif;

        if($data['bidding_contractors']):
            $data['status'] = 'Bidding';
        endif;


        $item_id = $pod->save( $data );
    endif;


    if($pod_name == "contractor"):
        if (isset($_POST['etype']) && $_POST['etype'] == 'own'):
            if ( ! $_POST['name'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['registry_or_license_number'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['business_address'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['state'] ):
                echo "-1<p class='box alert'>Please select state!</p>";die;
            endif;

            if ( ! $_POST['city'] ):
                echo "-1<p class='box alert'>Please select city!</p>";die;
            endif;

            if ( ! $_POST['zip_code'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['contact_name'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['title'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['phone_number'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['email_address'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['web_address'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['roofing_service'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['type_of_services'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['business_more_than_one_state'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['labour_crews'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['hold_osha_certification'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['business_information'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;

            if ( ! $_POST['interested_in_advertising'] ):
                echo "-1<p class='box alert'>Please fill the valid field!</p>";die;
            endif;
            $item_id = $pod->save( $_POST );

            if($_FILES && isset($_FILES['company_logo'])):
                $aid =[];
                fixFilesArray($_FILES['company_logo']);
                foreach ($_FILES['company_logo'] as $ckey => $rpfiles):
                    $aid[] = importImage($rpfiles);
                endforeach;
                $pod->save( 'company_logo', $aid, $id );
            endif;

            if(count($_POST['ref_name']) > 0):
                foreach ($_POST['ref_name'] as $rkey => $ref_name):
                    $rdata = [];
                    $pod_ri = pods('referral_information');
                    $rdata['ref_name'] = $ref_name;

                    if(isset($post['ref_phone_number'][$rkey])):
                        $rdata['ref_phone_number'] = $_POST['ref_phone_number'][$rkey];
                    endif;
                    if(isset($post['ref_email'][$rkey])):
                        $rdata['ref_email'] = $_POST['ref_email'][$rkey];
                    endif;
                    $userinfo = wp_get_current_user();
                    $user_id = $userinfo->ID;
                    $rdata['author'] = $user_id;
                    $rdata['contractors'] = $id;
                    $rfid = $pod_ri->add($rdata);
                    error_log('RF LOG:'.print_r($rfid, true));
                    $ref_id[] = $rfid;
                endforeach;
            endif;
        else:
            if(!isset($_POST['assign_roofing_request'])):
                $data['assign_roofing_request'] = [];
            endif;
            $item_id = $pod->save( $data );
            if(($_POST['status'] == 1) && ($prev_status == 0)):
                $user = $pod->field( 'author', null, true );
                $user_id = $user['ID'];
                $wpdb->query("UPDATE `wp_users` SET user_status = 0 WHERE ID = '{$user_id}';");
                $password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                wp_set_password( $password, $user_id );
                $user_data = get_user_by('ID', $user_id);
                $first_name = get_user_meta($user_id,'first_name', true);
                $mail = new RhMail();
                $email_body = $mail->new_account_email($first_name, $password,$user_data->user_email);
                $subject = "RoofHub: New Account Details";
                $send_result = $mail->send_general_email( $email_body,  $user_data->user_email, $subject, $user_data );
            endif;
        endif;
    endif;

    if($item_id > 0):
        echo "-1<p class='box tick'>Successfully updated!</p>";die;
    else:
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;
    die;
}


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


function fed_update_user_ad_processing(){
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'update-ad-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;

    if ( ! is_user_logged_in()) {
        echo "-1<p class='box alert'>Unauthorized access!</p>";die;
    }


    if ( ! $_POST['name'] ):
        echo "-1<p class='box alert'>Please enter name!</p>";die;
    endif;

    if ( ! $_POST['description'] ):
        echo "-1<p class='box alert'>Please enter description!</p>";die;
    endif;

    if ( ! $_POST['url_link'] ):
        echo "-1<p class='box alert'>Please enter redirected URL link !</p>";die;
    endif;

    if ( ! $_POST['state'] ):
        echo "-1<p class='box alert'>Please select a state !</p>";die;
    endif;

    $acceptable = array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    );



    if(isset($_FILES['sponsor_image']) && $_FILES['sponsor_image']['type']):
        if(!in_array($_FILES['sponsor_image']['type'], $acceptable) && (!empty($_FILES["sponsor_image"]["type"]))):
            echo "-1<p class='box alert'>Invalid file type. Only JPG, GIF and PNG types are accepted.</p>";die;
        endif;
    endif;

    $userinfo = wp_get_current_user();
    $post = $_POST;

    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    $pod = pods( 'advertisement', $id );
    $item_id = $pod->save( $post );


    if($_FILES && isset($_FILES['sponsor_image']) && $_FILES['sponsor_image']['type']):
        $aid = importImage($_FILES['sponsor_image']);
        $pod->save( 'sponsor_image', $aid, $id );
    endif;


    echo "<p class='box alert'>Advertisement have been successfully updated.</p>";die;
}


function fed_make_payment_processing(){
    global $wpdb;
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'rf-payment-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;

    if ( ! is_user_logged_in()) {
        echo "-1<p class='box alert'>Unauthorized access!</p>";die;
    }

    $pp = new WC_PP_PRO_Gateway();

    $valid = $pp->validate_fields();
    if($valid):
        echo "-1<p class='box alert'>{$valid}</p>";die;
    endif;

    $obj = new stdClass();
    $userinfo = wp_get_current_user();

    $id = encrypt_decrypt('decrypt', $_POST['id']);
    $params = array(
        'where' => ' t.id = "'.$id.'" ',
        'limit' => 1  // Return all rows
    );
    $advs = pods('adv_package', $params);

    while ( $advs->fetch() ):
        $raw_data = $advs->data();
        $raw_data = $raw_data[0];
    endwhile;
    $obj->user_id = $userinfo->ID;
    $obj->total = $raw_data->price;
    $obj->billing_first_name = get_user_meta($obj->user_id, 'first_name', true);
    $obj->billing_last_name = get_user_meta($obj->user_id, 'last_name', true);
    $obj->billing_city = get_user_meta($obj->user_id, 'city', true);
    $obj->billing_state = get_user_meta($obj->user_id, 'state', true);
    $obj->billing_postcode = get_user_meta($obj->user_id, 'zip_code', true);
    $obj->billing_address = get_user_meta($obj->user_id, 'address', true);


    $response = $pp->process_payment($obj);

    if($response['result'] = 'success'):
        $pod = pods('payment');
        $data = [];
        $data['name'] = $obj->billing_first_name." ".$obj->billing_last_name;
        $data['author'] = $userinfo->ID;
        $data['type'] = 'ad';
        $data['transaction_id'] = $response['data']['TRANSACTIONID'];
        $data['correlation_id'] = $response['data']['CORRELATIONID'];
        $data['status'] = '1';
        $data['amount'] = $response['data']['AMT'];
        $data['payments_made'] = date('Y-m-d H:i:s', strtotime($response['data']['TIMESTAMP']));
        $data['payments_note'] = "";
        $pod->add($data);



        $edata = [];
        $edata['ad_id'] = $raw_data->id;
        $edata['user_id'] = $userinfo->ID;
        $edata['no_card'] = $raw_data->no_of_cards;
        $edata['used_card'] = 0;
        $edata['type'] = $raw_data->type;
        $edata['date'] = date('Y-m-d H:i:s');
        $wpdb->insert(
            'wp_sponsor_member',
            $edata,
            array(
                '%d','%d','%d','%d','%d','%s'
            )
        );

    else:
        $pod = pods('payment');
        $data = [];
        $data['name'] = $obj->billing_first_name." ".$obj->billing_last_name;
        $data['author'] = $userinfo->ID;
        $data['type'] = 'ad';
        $data['transaction_id'] = $response['data']['TRANSACTIONID'];
        $data['correlation_id'] = $response['data']['CORRELATIONID'];
        $data['amount'] = $response['data']['AMT'];
        $data['status'] = '0';
        $data['payments_made'] = date('Y-m-d H:i:s', strtotime($response['data']['TIMESTAMP']));
        $data['payments_note'] = $response['err_msg_note'];
        $pod->add($data);
        echo "-1<p class='box alert'>{$response['err_msg']}</p>";die;
    endif;
    die;
}


function fed_add_user_ad_processing(){
    global $wpdb;
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'add-ad-nonce' ) ):
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;

    if ( ! is_user_logged_in()) {
        echo "-1<p class='box alert'>Unauthorized access!</p>";die;
    }


    if ( ! $_POST['name'] ):
        echo "-1<p class='box alert'>Please enter name!</p>";die;
    endif;

    if ( ! $_POST['description'] ):
        echo "-1<p class='box alert'>Please enter description!</p>";die;
    endif;

    if ( ! $_POST['url_link'] ):
        echo "-1<p class='box alert'>Please enter redirected URL link !</p>";die;
    endif;

    if ( ! $_POST['state'] ):
        echo "-1<p class='box alert'>Please select a state !</p>";die;
    endif;

    $acceptable = array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    );



    if(isset($_FILES['sponsor_image']) && $_FILES['sponsor_image']['type']):
        if(!in_array($_FILES['sponsor_image']['type'], $acceptable) && (!empty($_FILES["sponsor_image"]["type"]))):
            echo "-1<p class='box alert'>Invalid file type. Only JPG, GIF and PNG types are accepted.</p>";die;
        endif;
    endif;

    $userinfo = wp_get_current_user();
    $post = $_POST;


    $pod = pods( 'advertisement' );
    $item_id = $pod->add( $post );


    if($_FILES && isset($_FILES['sponsor_image']) && $_FILES['sponsor_image']['type']):
        $aid = importImage($_FILES['sponsor_image']);
        $pod->save( 'sponsor_image', $aid, $item_id );
    endif;

    if(current_user_can('sponsor')):
        $pieces = explode("__", $_POST['pack_info']);
        $member_id = $pieces[0];
        $package_id = $pieces[1];
        $pod->save( 'packages', $package_id , $item_id);

        $wpdb->query( $wpdb->prepare(
            "
                    UPDATE wp_sponsor_member 
                    SET used_card = used_card + 1
                    WHERE id = %d                    
                    ",
            $member_id
        ) );


        $people = pods( 'adv_package', $package_id );
        $adv = $people->row();

        if($adv['type'] == 2):
            $pod->save( 'is_premium', 1, $item_id );
        else:
            $pod->save( 'is_premium', 0, $item_id );
        endif;
        $pod->save( 'expire_date', date('Y-m-d H:i:s', strtotime('+'.$adv['no_duration'].' years')), $item_id );
    endif;


    echo "<p class='box alert'>Advertisement have been successfully added</p>";die;
}


function fed_get_ad_packeges_processing(){
    $type = $_POST['type'];
    if($type == "sponsor"):
        $tp = 1;
    else:
        $tp = 2;
    endif;
    $params = array(
        'where' => ' t.type = '.$tp.' ',
        'limit' => -1  // Return all rows
    );
    $res = pods( 'adv_package', $params );

    // Delete the current pod item

    $result = [];
    ob_start();
    if ( 0 < $res->total() ):
        $i = 0;
        while ( $res->fetch() ):
            $raw_data = $res->data();
            $raw_data = $raw_data[$i];
            ?>
            <div class="frb frb-warning">
                <input type="radio" id="radio-button-<?=$res->display('id')?>" class="js-select-p" name="package" value="<?=encrypt_decrypt('encrypt', $res->display('id'))?>">
                <label for="radio-button-<?=$res->display('id')?>">
                    <span class="frb-title"><?=$res->display('price')?> / <?=$res->display('duration')?></span><br>
                    <span class="frb-description">Only <?=$res->display('no_of_cards')?> card can publish for <?=$res->display('no_duration')?> <?=$res->display('duration')?></span>
                </label>
            </div>
            <?php
        endwhile;
   endif;
   $data = ob_get_contents();
   ob_get_clean();
    $result['data'] = $data;
    echo json_encode($result);die;
}

function fed_get_ad_location_processing(){
    $type = $_POST['type'];

    $params = array(
        'where' => ' t.type = '.$type.' ',
        'limit' => -1  // Return all rows
    );
    $res = pods( 'adv_location', $params );

    $result = [];
    ob_start();
    if ( 0 < $res->total() ):
        $i = 0;
        while ( $res->fetch() ):
            $raw_data = $res->data();
            $raw_data = $raw_data[$i];
            ?>
            <option value="<?=$res->display('id')?>"><?=$res->display('name')?></option>
        <?php
        endwhile;
    endif;
    $data = ob_get_contents();
    ob_get_clean();
    $result['data'] = $data;
    echo json_encode($result);die;
}

function fed_assign_rf_req_to_contract_processing(){
    global $wpdb;
    $id = $_POST['id'];
    $userinfo = wp_get_current_user();
    $id = encrypt_decrypt('decrypt',$id);
    $params = array(
        'where' => ' t.id = '.$id.' ',
        'limit' => 1  // Return all rows
    );
    $pod = pods( 'roofhub_request', $params );

    $bcdata = [];
    while ( $pod->fetch() ):
        $bc = $pod->field( 'bidding_contractors' , null, true  );
        if($bc):
            foreach($bc as $bkey => $b):
                $bcdata[] =  $b['ID'];
            endforeach;
        endif;
    endwhile;

    array_push($bcdata, $userinfo->ID);
    $pod = pods( 'roofhub_request', $id );
    $item = $pod->save( ['bidding_contractors' => $bcdata]);

    $where = 'author.ID = "' . $userinfo->ID . '"';
    $params = array(
        'where' => $where,
        'limit' => 1  // Return all rows
    );
    $contractor = pods( 'contractor', $params );

    $edata = [];
    $cid = '';
    while ($contractor->fetch()):
        $cid = $contractor->display('id');
        $val = $contractor->field('assign_roofing_request', null, true);
        if ($val):
            foreach ($val as $vkey => $v):
                if($id != $v['id']):
                    $edata[] = $v['id'];
                endif;
            endforeach;
        endif;
    endwhile;
    $cpod = pods( 'contractor', $cid );
    $item = $cpod->save( ['assign_roofing_request' => $edata]);
    $pod->save( ['status' => "Request"]);

    $result['msg'] = '<a class="btn btn-danger btn-xs" href="javascript://" role="button" alt="View" title="View">Bid Now</a>';
    echo json_encode($result);die;
}



/**
 * AJAX: Global pods data modify
 */
function fed_submit_bid_comment_processing(){
    global $wpdb;
    $id = $_POST['roofing_request'];
    $id = encrypt_decrypt('decrypt',$id);


    if ( ! $_POST['comments'] ):
        echo "-1<p class='box alert'>Please enter your comment!</p>";die;
    endif;


    $pod = pods( 'bidding_comment');


    $data = $_POST;
    $data['roofing_request'] = $id;
    $data['is_read'] = 0;
    $data['price_accept'] = 0;
    unset($data['_wpnonce']);
    unset($data['_wp_http_referer']);
    unset($data['noreset']);
    unset($data['redirect']);

    $item_id = $pod->add( $data );


    if($item_id > 0):
        echo "<p class='box tick'>Successfully submitted!</p>";die;
    else:
        echo "-1<p class='box alert'>Invalid form submissions!</p>";die;
    endif;
    die;
}


function fed_offer_accept_processing(){
    $id = $_POST['id'];
    $pod = pods( 'bidding_comment', $id);
    $item = $pod->save( ['price_accept' => 1]);

    $con = $_POST['con'];


    $rq = pods( 'roofhub_request', $_POST['rq'] );

    $item = $rq->save( ['assign_contractors' => $con]);
    $item = $rq->save( ['status' => 'Assign']);

    $result['msg'] = '<span class="label label-success">Accepted</span>';
    echo json_encode($result);die;
}