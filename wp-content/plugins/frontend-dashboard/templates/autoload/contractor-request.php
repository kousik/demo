<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 2/5/18
 * Time: 10:17 PM
 */


/**
 * Template Name: Roofing Request Template
 * Description: Used as a page template to show page contents, followed by a loop
 * through the "Genesis Office Hours" category
 */
remove_action( 'genesis_loop_else', 'genesis_do_noposts' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
// Add our custom loop
add_action( 'genesis_before_content', 'cd_goh_loop' );
function cd_goh_loop() {
    ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Submit A Contractor Application Request Now!</h2>
            <!-- multistep form -->
            <form id="etf-hub-form" name="etf-community-form" data-cl="contractor" class="msform" enctype="multipart/form-data" method="post">
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">Location</li>
                    <li>Business Contact Information</li>
                    <li>Business & Other Details</li>
                </ul>

                <!-- fieldsets -->
                <?php
                $rf = pods( 'contractor' );
                ?>
                <fieldset>
                    <div class="etf-community-ajax-feedback"></div>
                    <h2 class="fs-title">Select your location</h2>
                    <h3 class="fs-subtitle">Where are you located?</h3>
                    <label for="state" class="pull-left"> State </label>
                    <select data-placeholder="Choose a State..." class="chosen-select js-c-state" name="state" tabindex="2" required>
                        <option value=""></option>
                        <?php foreach (fdb_get_state() as $skey => $sobj):?>
                            <option value="<?=$sobj->name?>" data-id="<?=$sobj->id?>"><?=$sobj->name?></option>
                        <?php endforeach;?>
                    </select>
                    <label for="city" class="pull-left"> City </label>
                    <select data-placeholder="Choose a City..." class="chosen-select js-city" name="city" tabindex="2">
                        <option value=""></option>
                    </select>
                    <input type="button" name="next" class="cnext action-button" value="Next" />
                </fieldset>

                <fieldset>
                    <div class="etf-community-ajax-feedback"></div>
                    <h2 class="fs-title">Business Contact Information</h2>
                    <h3 class="fs-subtitle">All fields are required for your request</h3>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['name']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['name']['name']?>" placeholder="<?=$rf->pod_data['fields']['name']['label']?>" />
                    </div>


                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['registry_or_license_number']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['registry_or_license_number']['name']?>" placeholder="<?=$rf->pod_data['fields']['registry_or_license_number']['label']?>" />
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['business_address']['label']?><span style="color: red;">*</span></label>
                        <textarea name="<?=$rf->pod_data['fields']['business_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['business_address']['label']?>"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['zip_code']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['zip_code']['name']?>" placeholder="<?=$rf->pod_data['fields']['zip_code']['label']?>" value="" />
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['contact_name']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['contact_name']['name']?>" placeholder="<?=$rf->pod_data['fields']['contact_name']['label']?>" />
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['title']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['title']['name']?>" placeholder="<?=$rf->pod_data['fields']['title']['label']?>" />
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['phone_number']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['phone_number']['name']?>" placeholder="<?=$rf->pod_data['fields']['phone_number']['label']?>" />
                    </div>


                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['email_address']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['email_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['email_address']['label']?>" />
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['web_address']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['web_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['web_address']['label']?>" />
                    </div>






                    <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                    <input type="button" name="next" class="cnext action-button" value="Next" />

                    <div class="etf-community-ajax-feedback"></div>
                </fieldset>

                <fieldset>
                    <h2 class="fs-title">Business & Other Details</h2>
                    <h3 class="fs-subtitle">It will help you grow your business!</h3>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['roofing_service']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['roofing_service']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['roofing_service']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['type_of_services']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['type_of_services']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="checkbox" name="<?=$rf->pod_data['fields']['type_of_services']['name']?>[]" value="<?=trim($exp_vtype[0])?>" class="<?=$rf->pod_data['fields']['type_of_services']['name']?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['business_more_than_one_state']['label']?><span style="color: red;">*</span></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['business_more_than_one_state']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['business_more_than_one_state']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['labour_crews']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['labour_crews']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['labour_crews']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['hold_osha_certification']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['hold_osha_certification']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['hold_osha_certification']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['business_referrals']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $ri = pods( 'referral_information' );
                        ?>
                        <div class="rep-ref" style="padding: 30px;">
                            <div class="ref-box" style="border-bottom: 1px solid #ccc; margin-bottom: 10px;">
                                <div class="form-group">
                                    <label for="fname"><?=$ri->pod_data['fields']['ref_name']['label']?><span style="color: red;">*</span></label>
                                    <input type="text" name="<?=$ri->pod_data['fields']['ref_name']['name']?>[]" placeholder="<?=$ri->pod_data['fields']['ref_name']['label']?>" class="<?=$ri->pod_data['fields']['ref_name']['name']?>" />
                                </div>
                                <div class="form-group">
                                    <label for="fname"><?=$ri->pod_data['fields']['ref_phone_number']['label']?><span style="color: red;">*</span></label>
                                    <input type="text" name="<?=$ri->pod_data['fields']['ref_phone_number']['name']?>[]" placeholder="<?=$ri->pod_data['fields']['ref_phone_number']['label']?>" class="<?=$ri->pod_data['fields']['ref_phone_number']['name']?>" />
                                </div>
                                <div class="form-group">
                                    <label for="fname"><?=$ri->pod_data['fields']['ref_email']['label']?></label>
                                    <input type="text" name="<?=$ri->pod_data['fields']['ref_email']['name']?>[]" placeholder="<?=$ri->pod_data['fields']['ref_email']['label']?>" />
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="form-group">
                        <a class="btn btn-info js-add-html pull-right">Add+</a>
                    </div>


                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['business_information']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['business_information']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['business_information']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>



                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['interested_in_advertising']['label']?><span style="color: red;">*</span></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['interested_in_advertising']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['interested_in_advertising']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>


                    <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                    <input type="submit" name="rf-form-submit" class="submit action-button" value="Submit" />
                    <input type="hidden" name="fed_ajax_hook" value="contractor_request" />
                    <input type="hidden" name="noreset" value="true">
                    <input type="hidden" name="redirect" value="<?=site_url('contractor-request/success')?>">
                    <?php wp_nonce_field('add-roofing-request-nonce') ?>

                    <div class="form-group">
                        <div class="col-sm-8">
                            <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                            <div class="etf-community-ajax-feedback"></div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <?php
}
genesis();