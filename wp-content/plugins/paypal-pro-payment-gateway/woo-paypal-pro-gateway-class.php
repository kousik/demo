<?php



class WC_PP_PRO_Gateway {

    protected $PAYPAL_NVP_SIG_SANDBOX = "https://api-3t.sandbox.paypal.com/nvp";
    protected $PAYPAL_NVP_SIG_LIVE = "https://api-3t.paypal.com/nvp";
    protected $PAYPAL_NVP_PAYMENTACTION = "Sale";
    protected $PAYPAL_NVP_METHOD = "DoDirectPayment";
    protected $PAYPAL_NVP_API_VERSION = "84.0";
    protected $order = null;
    protected $transactionId = null;
    protected $transactionErrorMessage = null;
    protected $usesandboxapi = true;
    protected $securitycodehint = true;
    protected $apiusername = '';
    protected $apipassword = '';
    protected $apisigniture = '';
    protected $paymentresponse = '';

    public function __construct() {
        $this->id = 'paypalpro';//ID needs to be ALL lowercase or it doens't work
        $this->GATEWAYNAME = 'PayPal-Pro';
        $this->method_title = 'PayPal-Pro';
        $this->has_fields = true;

       // $this->init_form_fields();
        $this->init_settings();

        $this->description = '';
        $this->usesandboxapi = strcmp($this->settings['debug'], 'yes') == 0;
        $this->securitycodehint = strcmp($this->settings['securitycodehint'], 'yes') == 0;
        //If the field is populated, it will grab the value from there and will not be translated.  If it is empty, it will use the default and translate that value
        $this->title = strlen($this->settings['title']) > 0 ? $this->settings['title'] : __('Credit Card Payment', 'woocommerce');
        $this->apiusername = $this->settings['paypalapiusername'];
        $this->apipassword = $this->settings['paypalapipassword'];
        $this->apisigniture = $this->settings['paypalapisigniture'];        
        
        add_filter('http_request_version', array(&$this, 'use_http_1_1'));                
        add_action('admin_notices', array(&$this, 'handle_admin_notice_msg'));
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
        
    }

    public function init_settings(){
        $settings =  [];
        $pod_settings = pods( 'roofhub_settings');

        $settings['debug'] = $pod_settings->field( 'debug' );
        $settings['securitycodehint'] = $pod_settings->field( 'securitycodehint' );
        $settings['title'] = $pod_settings->field( 'title' );
        $settings['paypalapiusername'] = $pod_settings->field( 'paypalapiusername' );
        $settings['paypalapipassword'] = $pod_settings->field( 'paypalapipassword' );
        $settings['paypalapisigniture'] = $pod_settings->field( 'paypalapisigniture' );

        $this->settings = $settings;
    }


    /*
     * Validates the fields specified in the payment_fields() function.
     */
    public function validate_fields() {
        global $woocommerce;

        if (!WC_PP_PRO_Utility::is_valid_card_number($_POST['billing_credircard'])){
            return 'Credit card number you entered is invalid.';
        }
        if (!WC_PP_PRO_Utility::is_valid_card_type($_POST['billing_cardtype'])){
            return 'Card type is not valid.';
        }
        if (!WC_PP_PRO_Utility::is_valid_expiry($_POST['billing_expdatemonth'], $_POST['billing_expdateyear'])){
            return 'Card expiration date is not valid.';
        }
        if (!WC_PP_PRO_Utility::is_valid_cvv_number($_POST['billing_ccvnumber'])){
            return 'Card verification number (CVV) is not valid. You can find this number on your credit card.';
        }
    }
    
    /*
     * Render the credit card fields on the checkout page
     */
    public function payment_fields($id) {
        $billing_credircard = isset($_REQUEST['billing_credircard'])? esc_attr($_REQUEST['billing_credircard']) : '';
        ?>
        <form id="etf-hub-form" name="etf-community-form" class="password form-horizontal" action="<?php echo site_url( 'wp-load.php' );?>" method="post">
            <div class="form-group">
                <label class="col-sm-6 control-label"><?php _e('Card Number', 'woocommerce'); ?> <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input class="input-text" type="text" size="19" maxlength="19" name="billing_credircard" value="<?php echo $billing_credircard; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label"><?php _e('Card Type', 'woocommerce'); ?> <span class="required">*</span></label>
                <div class="col-sm-6">
                    <select name="billing_cardtype" style="width: 50%;">
                        <option value="Visa" selected="selected">Visa</option>
                        <option value="MasterCard">MasterCard</option>
                        <option value="Discover">Discover</option>
                        <option value="Amex">American Express</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label"><?php _e('Expiration Date', 'woocommerce'); ?> <span class="required">*</span></label>
                <div class="col-sm-6">
                    <select name="billing_expdatemonth" style="width: 20%">
                        <option value=1>01</option>
                        <option value=2>02</option>
                        <option value=3>03</option>
                        <option value=4>04</option>
                        <option value=5>05</option>
                        <option value=6>06</option>
                        <option value=7>07</option>
                        <option value=8>08</option>
                        <option value=9>09</option>
                        <option value=10>10</option>
                        <option value=11>11</option>
                        <option value=12>12</option>
                    </select>
                    <select name="billing_expdateyear" style="width: 20%">
                        <?php
                        $today = (int)date('Y', time());
                        for($i = 0; $i < 12; $i++)
                        {
                            ?>
                            <option value="<?php echo $today; ?>"><?php echo $today; ?></option>
                            <?php
                            $today++;
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label"><?php _e('Card Verification Number (CVV)', 'woocommerce'); ?> <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input class="input-text" type="text" size="4" maxlength="4" name="billing_ccvnumber" value="" style="width: 20%" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-6 col-sm-6">
                    <?php if ($this->securitycodehint){
                        $cvv_hint_img = WC_PP_PRO_ADDON_URL.'/images/card-security-code-hint.png';
                        $cvv_hint_img = apply_filters('wcpprog-cvv-image-hint-src', $cvv_hint_img);
                        echo '<div class="wcppro-security-code-hint-section">';
                        echo '<img src="'.$cvv_hint_img.'" />';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-6 col-sm-6">
                    <button type="submit" name="etf-hub-form-submit" class="btn btn-warning">Pay Now</button>
                </div>
            </div>
            <?php if(isset($_GET['ad-id']) && $_GET['ad-id']):?>
                <input type="hidden" name="adid" value="<?=$_GET['ad-id']?>" />
            <?php endif?>

            <input type="hidden" name="fed_ajax_hook" value="make_payment" />
            <input type="hidden" name="id" value="<?=$id?>" />
            <?php wp_nonce_field('rf-payment-nonce') ?>
            <input type="hidden" name="redirect" value="<?=site_url('sponsor-payment/success')?>" />

            <div class="form-group">
                <div class="col-sm-offset-6 col-sm-6">
                    <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                    <div class="etf-community-ajax-feedback"></div>
                </div>
            </div>
        </form>
        <div class="clear"></div>
        
        <?php
    }

    public function process_payment($obj) {
        $this->order = $obj;
        $gatewayRequestData = $this->create_paypal_request();

        if ($gatewayRequestData AND $this->verify_paypal_payment($gatewayRequestData)) {
            return array(
                'result' => 'success',
                'data' => $this->paymentresponse
            );
        } else {
            return array(
                'result' => 'error',
                'data' => $this->paymentresponse,
                'err_msg_note' => sprintf("Paypal Credit Card Payment Failed with message: '%s'", $this->transactionErrorMessage),
                'err_msg' => '(Transaction Error) something is wrong.'
            );
        }
    }

    /*
     * Set the HTTP version for the remote posts
     * https://developer.wordpress.org/reference/hooks/http_request_version/
     */
    public function use_http_1_1($httpversion) {
        return '1.1';
    }



    protected function verify_paypal_payment($gatewayRequestData) {
        global $woocommerce;

        $erroMessage = "";
        $api_url = $this->usesandboxapi ? $this->PAYPAL_NVP_SIG_SANDBOX : $this->PAYPAL_NVP_SIG_LIVE;
        $request = array(
            'method' => 'POST',
            'timeout' => 45,
            'blocking' => true,
            'sslverify' => $this->usesandboxapi ? false : true,
            'body' => $gatewayRequestData
        );

        $response = wp_remote_post($api_url, $request);
        if (!is_wp_error($response)) {
            $parsedResponse = $this->parse_paypal_response($response);
            error_log(print_r($parsedResponse, true));
            if (array_key_exists('ACK', $parsedResponse)) {
                $this->paymentresponse = $parsedResponse;

                switch ($parsedResponse['ACK']) {
                    case 'Success':
                    case 'SuccessWithWarning':
                        $this->transactionId = $parsedResponse['TRANSACTIONID'];
                        return true;
                        break;

                    default:
                        $this->transactionErrorMessage = $erroMessage = $parsedResponse['L_LONGMESSAGE0'];
                        return false;
                        break;
                }


            }

        } else {
            // Uncomment to view the http error
            //$erroMessage = print_r($response->errors, true);
            $erroMessage = 'Something went wrong while performing your request. Please contact website administrator to report this problem.';
        }

        //wc_add_notice($erroMessage,'error');
        //echo "-1<p class='box alert'>{$erroMessage}</p>";die;
        $this->transactionErrorMessage = $erroMessage;
        return false;
    }

    protected function parse_paypal_response($response) {
        $result = array();
        $enteries = explode('&', $response['body']);

        foreach ($enteries as $nvp) {
            $pair = explode('=', $nvp);
            if (count($pair) > 1)
                $result[urldecode($pair[0])] = urldecode($pair[1]);
        }

        return $result;
    }

    protected function create_paypal_request() {
        if ($this->order AND $this->order != null) {
            return array(
                'PAYMENTACTION' => $this->PAYPAL_NVP_PAYMENTACTION,
                'VERSION' => $this->PAYPAL_NVP_API_VERSION,
                'METHOD' => $this->PAYPAL_NVP_METHOD,
                'PWD' => $this->apipassword,
                'USER' => $this->apiusername,
                'SIGNATURE' => $this->apisigniture,
                'AMT' => $this->order->total,
                'FIRSTNAME' => $this->order->billing_first_name,
                'LASTNAME' => $this->order->billing_last_name,
                'CITY' => $this->order->billing_city,
                'STATE' => $this->order->billing_state,
                'ZIP' => $this->order->billing_postcode,
                'COUNTRYCODE' => 'US',
                'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
                'CREDITCARDTYPE' => $_POST['billing_cardtype'],
                'ACCT' => $_POST['billing_credircard'],
                'CVV2' => $_POST['billing_ccvnumber'],
                'EXPDATE' => sprintf('%s%s', $_POST['billing_expdatemonth'], $_POST['billing_expdateyear']),
                'STREET' => $this->order->billing_address,
                'CURRENCYCODE' => "USD",
                'BUTTONSOURCE' => 'TipsandTricks_SP',
            );
        }
        return false;
    }
    
}//End of class