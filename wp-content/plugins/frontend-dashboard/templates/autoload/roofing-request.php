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
            <h2>Submit A Roofing Request Now!</h2>
            <!-- multistep form -->
            <form id="etf-hub-form" name="etf-community-form" data-cl="roofing" class="msform" enctype="multipart/form-data" method="post">
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">Location</li>
                    <li>Roofing Request</li>
                    <li>Personal Details</li>
                </ul>

                <!-- fieldsets -->

                <?php
                $rf = pods( 'roofhub_request' );
                ?>


                <fieldset>
                    <div class="etf-community-ajax-feedback"></div>
                    <h2 class="fs-title">Select your location</h2>
                    <h3 class="fs-subtitle">Where are you located?</h3>
                    <label for="state" class="pull-left"> State </label>
                    <select data-placeholder="Choose a State..." class="chosen-select js-state" name="state" tabindex="2" required>
                        <option value=""></option>
                        <?php foreach (fdb_get_state() as $skey => $sobj):?>
                            <option value="<?=$sobj->name?>" data-id="<?=$sobj->id?>"><?=$sobj->name?></option>
                        <?php endforeach;?>
                    </select>
                    <label for="city" class="pull-left"> City </label>
                    <select data-placeholder="Choose a City..." class="chosen-select js-city" name="city" tabindex="2">
                        <option value=""></option>
                    </select>
                    <input type="button" name="next" class="next action-button" value="Next" />
                </fieldset>

                <fieldset>
                    <div class="etf-community-ajax-feedback"></div>
                    <h2 class="fs-title">Roofing Request </h2>
                    <h3 class="fs-subtitle">All fields are required for your raise a request</h3>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['building_type']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['building_type']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['building_type']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['building_level']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['building_level']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['building_level']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['roof_type']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['roof_type']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['roof_type']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['property_accessible']['label']?><span style="color: red;">*</span></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['property_accessible']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['property_accessible']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['roof_accessible']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['roof_accessible']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['roof_accessible']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['roof_cond']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['roof_cond']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['roof_cond']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>


                    <div class="form-group js-pitch-type" style="display: none;">
                        <label for="fname"><?=$rf->pod_data['fields']['pitch_type']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['pitch_type']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['pitch_type']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>


                    <div class="form-group js-flat-type" style="display: none;">
                        <label for="fname"><?=$rf->pod_data['fields']['flat_type']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['flat_type']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['flat_type']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>


                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['visible_water_damage']['label']?><span style="color: red;">*</span></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['visible_water_damage']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['visible_water_damage']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['any_skylights']['label']?><span style="color: red;">*</span></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['any_skylights']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['any_skylights']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['any_satellite']['label']?><span style="color: red;">*</span></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['any_satellite']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['any_satellite']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['visible_damage']['label']?><span style="color: red;">*</span></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['visible_damage']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['visible_damage']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['insurance_claim']['label']?><span style="color: red;">*</span></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['insurance_claim']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['insurance_claim']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['time_frame_needed']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['time_frame_needed']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['time_frame_needed']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['pictures_to_upload']['label']?></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['pictures_to_upload']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['pictures_to_upload']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>

                    <div class="form-group js-rf-pics" style="display: none;">
                        <label for="fname"><?=$rf->pod_data['fields']['roof_pictures']['label']?></label>
                        <div class="radio">
                            <input type="file" id="exampleInputFile" name="<?=$rf->pod_data['fields']['roof_pictures']['name']?>[]" multiple>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['general_contractor']['label']?></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['general_contractor']['name']?>" value="Yes"> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rf->pod_data['fields']['general_contractor']['name']?>" value="No"> &nbsp;No
                            </label>
                        </div>
                    </div>


                    <div class="form-group js-elv-info" style="display:none;">
                        <label for="fname"><?=$rf->pod_data['fields']['roof_elevations_information']['label']?></label>
                        <div class="textarea">
                            <textarea name="<?=$rf->pod_data['fields']['roof_elevations_information']['name']?>"></textarea>
                        </div>
                    </div>

                    <div class="form-group js-elv-file" style="display: none;">
                        <label for="fname"><?=$rf->pod_data['fields']['roof_elevations_files']['label']?></label>
                        <div class="radio">
                            <input type="file" id="exampleInputFile" name="<?=$rf->pod_data['fields']['roof_elevations_files']['name']?>[]" multiple>
                        </div>
                    </div>




                    <input type="button" name="previous" class="previous action-button" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next" />

                    <div class="etf-community-ajax-feedback"></div>
                </fieldset>
                <?php
                $current_user = wp_get_current_user();
                if ( 0 == $current_user->ID ) {
                    $first_name = "";
                    $last_name = "";
                    $email_address = "";
                    $phone_number = "";
                    $address = "";
                    $zip_code = "";
                } else {
                    $first_name = get_user_meta($current_user->ID, 'first_name', true);
                    $last_name = get_user_meta($current_user->ID, 'last_name', true);
                    $email_address = $current_user->user_email;
                    $phone_number = get_user_meta($current_user->ID, 'phone_number', true);
                    $address = get_user_meta($current_user->ID, 'address', true);
                    $zip_code = get_user_meta($current_user->ID, 'zip_code', true);
                }

                ?>
                <fieldset>
                    <h2 class="fs-title">Personal Details</h2>
                    <h3 class="fs-subtitle">We will never sell it</h3>
                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['first_name']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['first_name']['name']?>" placeholder="<?=$rf->pod_data['fields']['first_name']['label']?>" value="<?=$first_name?>" />
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['last_name']['label']?></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['last_name']['name']?>" placeholder="<?=$rf->pod_data['fields']['last_name']['label']?>"  value="<?=$last_name?>"/>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['email_address']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['email_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['email_address']['label']?>" value="<?=$email_address?>" <?=$email_address?"disabled":""?>/>
                        <?php if($email_address):?>
                            <input type="hidden" name="<?=$rf->pod_data['fields']['email_address']['name']?>" value="<?=$email_address?>" />
                        <?php endif;?>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['phone_number']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['phone_number']['name']?>" placeholder="<?=$rf->pod_data['fields']['phone_number']['label']?>" value="<?=$phone_number?>" />
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['address']['label']?><span style="color: red;">*</span></label>
                        <textarea name="<?=$rf->pod_data['fields']['address']['name']?>" placeholder="<?=$rf->pod_data['fields']['address']['label']?>"><?=$address?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['zip_code']['label']?><span style="color: red;">*</span></label>
                        <input type="text" name="<?=$rf->pod_data['fields']['zip_code']['name']?>" placeholder="<?=$rf->pod_data['fields']['zip_code']['label']?>" value="<?=$zip_code?>" />
                    </div>

                    <div class="form-group">
                        <label for="fname"><?=$rf->pod_data['fields']['best_time_contact']['label']?><span style="color: red;">*</span></label>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['best_time_contact']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rf->pod_data['fields']['best_time_contact']['name']?>" value="<?=trim($exp_vtype[0])?>"> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>



                    <input type="button" name="previous" class="previous action-button" value="Previous" />
                    <input type="submit" name="rf-form-submit" class="submit action-button" value="Submit" />
                    <input type="hidden" name="fed_ajax_hook" value="roofing_request" />
                    <input type="hidden" name="noreset" value="true">
                    <input type="hidden" name="redirect" value="<?=site_url('roofing-request/success')?>">
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