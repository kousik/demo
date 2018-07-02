<?php
$rc = pods( 'contractor' );
$userinfo = wp_get_current_user();
$where = 'author.ID = "' . $userinfo->ID . '"';
$params = array(
    'where' => $where,
    'limit' => 1  // Return all rows
);


// Create and find in one shot
$contractor = pods( 'contractor', $params );
$contractor_oth = pods( 'contractor', $params );

$raw_data = $contractor->data();
$raw_data = $raw_data[0];
//$view = site_url('dashboard/edit-contractor-info/').encrypt_decrypt('encrypt', $raw_data->id);
$view = site_url("/dashboard/")."?menu_type=user&menu_slug=contractor_information&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=edit&rid=".encrypt_decrypt('encrypt',$raw_data->id);
?>

<div class="response" style="display: none;"></div>
<div class="js-table-content">

    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-warning" href="<?=$view?>" role="button" alt="Update" title="Update"><i class="fa fa-edit" aria-hidden="true"></i></a>
        </div>
    </div>



    <ul class="nav nav-tabs">
        <li class="active">
            <a  href="#business" data-toggle="tab">Business Contact Information</a>
        </li>
        <li><a href="#business-other" data-toggle="tab">Business Other Information</a>
        </li>
    </ul>

    <div class="tab-content ">
        <div class="tab-pane well well-sm active" id="business">
            <h3>Information:</h3>

            <?php
            while ( $contractor->fetch() ):?>
                <?php
                if($contractor->display( 'company_logo' )):
                    ?>
                    <div class="form-group">

                        <?php
                        $gallery = $contractor->field('company_logo', true);
                        $feat_image_url = wp_get_attachment_url( $gallery['ID'] );?>
                        <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="<?=$gallery['post_title']?>" data-caption="" data-image="<?=$feat_image_url?>" data-target="#image-gallery">
                            <img class="img-responsive" src="<?=$feat_image_url?>" alt="<?=$gallery['post_title']?>"  style="height: 200px;">
                        </a>
                    </div>
                <?php endif;?>
                <dl>
                    <dt><?=$rc->pod_data['fields']['name']['label']?></dt>
                    <dd><?=$contractor->display( 'name' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['registry_or_license_number']['label']?></dt>
                    <dd><?=$contractor->display( 'registry_or_license_number' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['business_address']['label']?></dt>
                    <dd><?=$contractor->display( 'business_address' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['city']['label']?></dt>
                    <dd><?=$contractor->display( 'city' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['state']['label']?></dt>
                    <dd><?=$contractor->display( 'state' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['zip_code']['label']?></dt>
                    <dd><?=$contractor->display( 'zip_code' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['title']['label']?></dt>
                    <dd><?=$contractor->display( 'title' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['contact_name']['label']?></dt>
                    <dd><?=$contractor->display( 'contact_name' )?></dd>
                </dl>

                <dl>
                    <dt><?=$rc->pod_data['fields']['phone_number']['label']?></dt>
                    <dd><?=$contractor->display( 'phone_number' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['email_address']['label']?></dt>
                    <dd><?=$contractor->display( 'email_address' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['web_address']['label']?></dt>
                    <dd><?=$contractor->display( 'web_address' )?></dd>
                </dl>
            <?php
            endwhile;
            ?>

        </div>
        <div class="tab-pane well well-sm" id="business-other">
            <h3>Other Information:</h3>
            <?php
            while ( $contractor_oth->fetch() ):?>
                <dl>
                    <dt><?=$rc->pod_data['fields']['roofing_service']['label']?></dt>
                    <dd><?=$contractor_oth->display( 'roofing_service' )?></dd>
                </dl>
                <dl>
                    <dt>What type of services does your company provide? </dt>
                    <dd><?=$contractor_oth->display( 'type_of_services' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['business_more_than_one_state']['label']?></dt>
                    <dd><?=$contractor_oth->display( 'business_more_than_one_state')?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['labour_crews']['label']?></dt>
                    <dd><?=$contractor_oth->display( 'labour_crews' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['hold_osha_certification']['label']?></dt>
                    <dd><?=$contractor_oth->display( 'hold_osha_certification' )?></dd>
                </dl>
                <dl>
                    <dt>Business Referrals</dt>
                    <dd>
                        <?php
                        if($contractor_oth->display( 'business_referrals' )):
                            foreach ($contractor_oth->field( 'business_referrals' , null, true  ) as $key => $ref): ?>
                                <ul class="well well-sm">
                                    <li class=""><strong>Name:</strong>  <?=$ref['ref_name']?></li>
                                    <li class=""><strong>Email:</strong>  <?=$ref['ref_email']?></li>
                                    <li class=""><strong>Phone Number:  </strong><?=$ref['ref_phone_number']?></li>
                                </ul>
                            <?php
                            endforeach;;
                        else:
                            echo "Not mentioned!";
                        endif;
                        ?>
                    </dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['business_information']['label']?></dt>
                    <dd><?=$contractor_oth->display( 'business_information')?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['interested_in_advertising']['label']?></dt>
                    <dd><?=$contractor_oth->display( 'interested_in_advertising' )?></dd>
                </dl>
            <?php
                //print_r($contractor_oth);
            endwhile;
            ?>
        </div>
    </div>

</div>
<div class="js-content-body" style="display: none;"></div>

<?php
//print_r($_GET);
if(isset($_GET['display']) && $_GET['display'] == 'edit'):
    if(isset($_GET['rid']) && $_GET['rid']):
        $id = encrypt_decrypt('decrypt',$_GET['rid']);
        ?>
        <script type="application/javascript">
            jQuery(document).ready(function () {
                jQuery.ajax({
                    method: "POST",
                    url: etajaxurl,
                    data: {
                        fed_ajax_hook: "contractor_edit",
                        id: '<?=$_GET['rid']?>',
                        type: 'own'
                    },
                    dataType: 'json',
                    success: function (data) {
                        jQuery('.js-table-content').hide(100);
                        jQuery('div.js-content-body').html(data.html);
                        jQuery('div.js-content-body').show();

                        jq(".js-profile-state").chosen();
                        jq(".js-profile-city").chosen();
                        jq(".js-profile-state").change(function(){
                            jq.post(etajaxurl, {
                                state_id: jq(this).val(),
                                fed_ajax_hook: "get_city_data"
                            }, function (data) {
                                jq('.js-profile-city').empty();
                                jq('.js-profile-city').html(data);
                                //jq(".js-city").chosen("destroy");
                                jq(".js-profile-city").trigger("chosen:updated");
                            }, "html");
                        });





                    }
                });
            });
        </script>
    <?php
    endif;
endif;
?>