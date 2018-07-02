<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 9/5/18
 * Time: 12:29 AM
 */

add_action('genesis_after', 'roofing_request_form', 11);
add_action('genesis_after', 'contractor_request_form', 12);

add_action('fed_contractor_view_processing', 'fed_contractor_view_html', 12);
add_action('fed_contractor_edit_processing', 'fed_contractor_edit_html', 12);

add_action('fed_roofing_view_processing', 'fed_roofing_view_html', 12);
add_action('fed_roofing_edit_processing', 'fed_roofing_edit_html', 12);

/**
 * @return bool
 */
function roofing_request_form() {
    if(epic_uri_slot() == 'dashboard'){
        return false;
    }
    ?>
    <!-- Button trigger modal -->
    <!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#rfModal">
        Launch demo modal
    </button>-->

    <!-- Modal -->
    <div class="modal fade" id="rfModal" tabindex="-1" role="dialog" aria-labelledby="rfModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Submit Your Roofing Request Now!</h4>
                </div>
                <div class="modal-body">
                    <form id="etf-hub-form-rf" name="etf-community-form" data-cl="roofing" class="modalform" enctype="multipart/form-data" method="post">
                        <?php
                        $rf = pods( 'roofhub_request' );
                        $curr_state = ip_info("Visitor", "State");
                        ?>
                        <div class="etf-community-ajax-feedback"></div>
                        <fieldset>
                            <h3 class="fs-subtitle">Where are you located?</h3>
                            <div class="form-group">
                                <label for="state" class="control-label"> State </label>
                                <select data-placeholder="Choose a State..." class="chosen-select js-state form-control" name="state" tabindex="2" required>
                                    <option value=""></option>
                                    <?php foreach (fdb_get_state() as $skey => $sobj):?>
                                        <option value="<?=$sobj->name?>" data-id="<?=$sobj->id?>" <?=$curr_state==$sobj->name?'selected':''?>><?=$sobj->name?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label for="city" class="control-label"> City </label>
                                <select data-placeholder="Choose a City..." class="chosen-select js-city" name="city" tabindex="2">
                                    <option value=""></option>
                                </select>
                            </div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>


                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>

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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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

                            <div class="js-ins-info" style="display: none;">
                                <div class="form-group">
                                    <label for="fname"><?=$rf->pod_data['fields']['insurance_provider_name']['label']?><span style="color: red;">*</span></label>
                                    <div class="radio">
                                        <input type="text" id="insurance_provider_name" name="<?=$rf->pod_data['fields']['insurance_provider_name']['name']?>" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="fname"><?=$rf->pod_data['fields']['claims_agent_name']['label']?><span style="color: red;">*</span></label>
                                    <div class="radio">
                                        <input type="text" id="claims_agent_name" name="<?=$rf->pod_data['fields']['claims_agent_name']['name']?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="fname"><?=$rf->pod_data['fields']['claims_agents_phone_number']['label']?><span style="color: red;">*</span></label>
                                    <div class="radio">
                                        <input type="text" id="claims_agents_phone_number" name="<?=$rf->pod_data['fields']['claims_agents_phone_number']['name']?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="fname"><?=$rf->pod_data['fields']['already_claimed']['label']?></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="<?=$rf->pod_data['fields']['already_claimed']['name']?>" value="Yes"> &nbsp;Yes
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="<?=$rf->pod_data['fields']['already_claimed']['name']?>" value="No" checked> &nbsp;No
                                        </label>
                                    </div>
                                </div>
                            </div>






                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                                    <input type="file" id="rfpics" name="<?=$rf->pod_data['fields']['roof_pictures']['name']?>[]" multiple>
                                </div>
                            </div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />

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
                            <h3 class="fs-subtitle">We will never sell it.</h3>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['first_name']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['first_name']['name']?>" placeholder="<?=$rf->pod_data['fields']['first_name']['label']?>" value="<?=$first_name?>" />
                            </div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['last_name']['label']?></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['last_name']['name']?>" placeholder="<?=$rf->pod_data['fields']['last_name']['label']?>"  value="<?=$last_name?>"/>
                            </div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group rfvalid">
                                <label for="fname"><?=$rf->pod_data['fields']['email_address']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['email_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['email_address']['label']?>" value="<?=$email_address?>" <?=$email_address?"disabled":""?>/>
                                <?php if($email_address):?>
                                    <input type="hidden" name="<?=$rf->pod_data['fields']['email_address']['name']?>" value="<?=$email_address?>" />
                                <?php endif;?>
                            </div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group rfvalid">
                                <label for="fname"><?=$rf->pod_data['fields']['phone_number']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['phone_number']['name']?>" placeholder="<?=$rf->pod_data['fields']['phone_number']['label']?>" value="<?=$phone_number?>" />
                            </div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['address']['label']?><span style="color: red;">*</span></label>
                                <textarea name="<?=$rf->pod_data['fields']['address']['name']?>" placeholder="<?=$rf->pod_data['fields']['address']['label']?>"><?=$address?></textarea>
                            </div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group rfvalid">
                                <label for="fname"><?=$rf->pod_data['fields']['zip_code']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['zip_code']['name']?>" placeholder="<?=$rf->pod_data['fields']['zip_code']['label']?>" value="<?=$zip_code?>" />
                            </div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="rf-form-submit" class="submit action-button pull-right" value="Submit" />
                            <input type="hidden" name="fed_ajax_hook" value="roofing_request" />
                            <input type="hidden" name="noreset" value="true">
                            <input type="hidden" name="redirect" value="<?=site_url('roofing-request/success')?>">
                            <?php wp_nonce_field('add-roofing-request-nonce') ?>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
}

/**
 * @return bool
 */
function contractor_request_form() {
    if(epic_uri_slot() == 'dashboard'){
        return false;
    }
    ?>
    <!-- Button trigger modal -->
    <!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#conModal">
        Launch demo modal
    </button>-->

    <!-- Modal -->
    <div class="modal fade" id="conModal" tabindex="-1" role="dialog" aria-labelledby="conModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Submit Your Contractor Application Now</h4>
                </div>
                <div class="modal-body">
                    <form id="etf-hub-form" name="etf-community-form" data-cl="contractor" class="modalform" enctype="multipart/form-data" method="post">
                        <div class="etf-community-ajax-feedback"></div>
                        <!-- fieldsets -->
                        <?php
                        $rf = pods( 'contractor' );
                        $curr_state = ip_info("Visitor", "State");
                        ?>
                        <fieldset>
                            <h2 class="fs-title">Select your state!</h2>
                            <div class="form-group">
                                <label for="state" class="pull-left"> State </label>
                                <select data-placeholder="Choose a State..." class="chosen-select js-c-state" name="state" tabindex="2" required>
                                    <option value=""></option>
                                    <?php foreach (fdb_get_state() as $skey => $sobj):?>
                                        <option value="<?=$sobj->name?>" data-id="<?=$sobj->id?>" <?=$curr_state==$sobj->name?'selected':''?>><?=$sobj->name?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="city" class="pull-left"> City </label>
                                <select data-placeholder="Choose a City..." class="chosen-select js-c-city" name="city" tabindex="2">
                                    <option value=""></option>
                                </select>
                            </div>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <h2 class="fs-title">Business Contact Information</h2>
                            <h3 class="fs-subtitle">All fields are required for your request</h3>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['name']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['name']['name']?>" placeholder="<?=$rf->pod_data['fields']['name']['label']?>" />
                            </div>

                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['company_logo']['label']?></label>
                                <div class="radio">
                                    <input type="file" id="exampleInputFile" name="<?=$rf->pod_data['fields']['company_logo']['name']?>[]">
                                </div>
                            </div>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['registry_or_license_number']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['registry_or_license_number']['name']?>" placeholder="<?=$rf->pod_data['fields']['registry_or_license_number']['label']?>" />
                            </div>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['business_address']['label']?><span style="color: red;">*</span></label>
                                <textarea name="<?=$rf->pod_data['fields']['business_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['business_address']['label']?>"></textarea>
                            </div>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group convalid">
                                <label for="fname"><?=$rf->pod_data['fields']['zip_code']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['zip_code']['name']?>" placeholder="<?=$rf->pod_data['fields']['zip_code']['label']?>" value="" />
                            </div>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['title']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['title']['name']?>" placeholder="<?=$rf->pod_data['fields']['title']['label']?>" />
                            </div>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['contact_name']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['contact_name']['name']?>" placeholder="<?=$rf->pod_data['fields']['contact_name']['label']?>" />
                            </div>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group convalid">
                                <label for="fname"><?=$rf->pod_data['fields']['phone_number']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['phone_number']['name']?>" placeholder="<?=$rf->pod_data['fields']['phone_number']['label']?>" />
                            </div>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group convalid">
                                <label for="fname"><?=$rf->pod_data['fields']['email_address']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['email_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['email_address']['label']?>" />
                            </div>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="fname"><?=$rf->pod_data['fields']['web_address']['label']?><span style="color: red;">*</span></label>
                                <input type="text" name="<?=$rf->pod_data['fields']['web_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['web_address']['label']?>" />
                            </div><input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
                            <h2 class="fs-title">Business & Other Details</h2>
                            <h3 class="fs-subtitle">It will help you grow your business!</h3>
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="previous" class="cprevious action-button" value="Previous" />
                            <input type="button" name="next" class="cnext action-button pull-right" value="Next" />
                        </fieldset>

                        <fieldset>
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
                            <input type="button" name="rf-form-submit" class="submit action-button pull-right" value="Submit" />
                            <input type="hidden" name="fed_ajax_hook" value="contractor_request" />
                            <input type="hidden" name="noreset" value="true">
                            <input type="hidden" name="redirect" value="<?=site_url('contractor-request/success')?>">
                            <?php wp_nonce_field('add-contractor-request-nonce') ?>

                            <div class="form-group">
                                <div class="col-sm-8">
                                    <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                                </div>
                            </div>
                        </fieldset>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
}


/**
 * AJAX: Contractor view details html
 */
function fed_contractor_view_html(){
    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    $rc = pods( 'contractor' );
    $data = [];
    ob_start();

    $params = array(
        'where'   => 't.id = '.$id,
        'limit'   => 1  // Return all rows
    );

    // Create and find in one shot
    $contractor = pods( 'contractor', $params );
    $contractor_oth = pods( 'contractor', $params );

    ?>
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-warning js-edit-data" data-action="contractor_edit" data-req="<?=encrypt_decrypt('encrypt',$id)?>" href="javascript://" role="button" alt="Update" title="Update"><i class="fa fa-edit" aria-hidden="true"></i></a> <a class="btn btn-default js-close" href="javascript://" role="button" alt="Close" data-req="<?=encrypt_decrypt('encrypt',$id)?>" title="Close"><i class="fa fa-close" aria-hidden="true"></i></a>
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
                    <dt><?=$rc->pod_data['fields']['type_of_services']['label']?></dt>
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
                    <dt><?=$rc->pod_data['fields']['business_referrals']['label']?></dt>
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
    </div><?php

    $message = ob_get_contents();
    ob_end_clean();
    $data['html'] =  $message;
    echo json_encode($data);die;
}


/**
 * AJAX: Contractor edit & update html
 */
function fed_contractor_edit_html(){
    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    $rc = pods( 'contractor' );
    $data = [];
    ob_start();

    $params = array(
        'where'   => 't.id = '.$id,
        'limit'   => 1  // Return all rows
    );

    // Create and find in one shot
    $contractor = pods( 'contractor', $params );
    $raw_data = $contractor->data();
    $raw_data = $raw_data[0];
    ?>
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-info js-view-data" data-action="contractor_view" data-req="<?=encrypt_decrypt('encrypt',$id)?>" href="javascript://" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a> <a class="btn btn-default js-close" href="javascript://" role="button" alt="Close" data-req="<?=encrypt_decrypt('encrypt',$id)?>" title="Close"><i class="fa fa-close" aria-hidden="true"></i></a>
        </div>
    </div>

    <?php
    while ( $contractor->fetch() ):
        ?>

        <ul class="nav nav-tabs">
            <li class="active">
                <a  href="#business" data-toggle="tab"><?=$contractor->display( 'name' )?></a>
            </li>
        </ul>

        <div class="tab-content ">
        <div class="tab-pane well well-sm active" id="business">
        <form id="etf-hub-form"  action="<?php echo site_url( 'wp-load.php' );?>" class="form-horizontal" method="post" enctype="multipart/form-data">

            <?php if(isset($_POST['type']) && $_POST['type'] == 'own'):?>
                <h3 class="fs-title">Business Contact Information</h3>
                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['name']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="<?=$rc->pod_data['fields']['name']['name']?>" placeholder="<?=$rc->pod_data['fields']['name']['label']?>" value="<?=$contractor->display( 'name' )?>" />
                    </div>
                </div>

                <?php
                    if($contractor->display( 'company_logo' )):
                ?>
                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label">Current Logo</label>
                    <div class="col-sm-10">
                            <?php
                            $gallery = $contractor->field('company_logo', true);
                            $feat_image_url = wp_get_attachment_url( $gallery['ID'] );?>
                            <div class="thumb">
                                <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="<?=$gallery['post_title']?>" data-caption="" data-image="<?=$feat_image_url?>" data-target="#image-gallery">
                                    <img class="img-responsive" src="<?=$feat_image_url?>" alt="<?=$gallery['post_title']?>"  style="height: 200px;">
                                </a>
                            </div>
                    </div>
                </div>
            <?php endif;?>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['company_logo']['label']?></label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <input type="file" id="exampleInputFile" name="<?=$rc->pod_data['fields']['company_logo']['name']?>[]">
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['registry_or_license_number']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="<?=$rc->pod_data['fields']['registry_or_license_number']['name']?>" placeholder="<?=$rc->pod_data['fields']['registry_or_license_number']['label']?>" value="<?=$contractor->display( 'registry_or_license_number' )?>"  />
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['business_address']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <textarea name="<?=$rc->pod_data['fields']['business_address']['name']?>" placeholder="<?=$rc->pod_data['fields']['business_address']['label']?>"> <?=$raw_data->business_address?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="state" class="col-sm-2 control-label"> State </label>
                    <div class="col-sm-10">
                        <select data-placeholder="Choose a State..." class="chosen-select js-profile-state" name="state" tabindex="2" required>
                            <option value=""></option>
                            <?php foreach (fdb_get_state() as $skey => $sobj):?>
                                <option value="<?=$sobj->name?>" data-id="<?=$sobj->id?>" <?=$raw_data->state == $sobj->name?"selected":""?>><?=$sobj->name?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="city" class="col-sm-2 control-label"> City </label>
                    <div class="col-sm-10">
                        <select data-placeholder="Choose a City..." class="chosen-select js-profile-city" name="city" tabindex="2">
                            <?php foreach (fdb_get_city($raw_data->state) as $ckey => $cobj):?>
                                <option value="<?=$cobj->name?>" data-id="<?=$cobj->id?>" <?=$raw_data->city == $cobj->name?"selected":""?>><?=$cobj->name?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['zip_code']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="<?=$rc->pod_data['fields']['zip_code']['name']?>" placeholder="<?=$rc->pod_data['fields']['zip_code']['label']?>"  value="<?=$contractor->display( 'zip_code' )?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['contact_name']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="<?=$rc->pod_data['fields']['contact_name']['name']?>" placeholder="<?=$rc->pod_data['fields']['contact_name']['label']?>" value="<?=$contractor->display( 'contact_name' )?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['title']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="<?=$rc->pod_data['fields']['title']['name']?>" placeholder="<?=$rc->pod_data['fields']['title']['label']?>" value="<?=$contractor->display( 'title' )?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['phone_number']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="<?=$rc->pod_data['fields']['phone_number']['name']?>" placeholder="<?=$rc->pod_data['fields']['phone_number']['label']?>"  value="<?=$contractor->display( 'phone_number' )?>" />
                    </div>
                </div>


                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['email_address']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="<?=$rc->pod_data['fields']['email_address']['name']?>" placeholder="<?=$rc->pod_data['fields']['email_address']['label']?>"  value="<?=$contractor->display( 'email_address' )?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['web_address']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="<?=$rc->pod_data['fields']['web_address']['name']?>" placeholder="<?=$rc->pod_data['fields']['web_address']['label']?>"  value="<?=$contractor->display( 'web_address' )?>" />
                    </div>
                </div>


                <h3 class="fs-title">Business & Other Details</h3>
                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['roofing_service']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <?php
                        $as = nl2br($rc->pod_data['fields']['roofing_service']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rc->pod_data['fields']['roofing_service']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->roofing_service == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['type_of_services']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <?php
                        $as = nl2br($rc->pod_data['fields']['type_of_services']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="checkbox" name="<?=$rc->pod_data['fields']['type_of_services']['name']?>[]" value="<?=trim($exp_vtype[0])?>" class="<?=$rc->pod_data['fields']['type_of_services']['name']?>"  <?=in_array(trim($exp_vtype[0]), $contractor->field( 'type_of_services' , null, true  ))?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['business_more_than_one_state']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rc->pod_data['fields']['business_more_than_one_state']['name']?>" value="Yes"  <?=$contractor->display('business_more_than_one_state') == 'Yes'?"checked":""?>> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rc->pod_data['fields']['business_more_than_one_state']['name']?>" value="No"  <?=$contractor->display('business_more_than_one_state') == "No"?"checked":""?>> &nbsp;No
                            </label>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['labour_crews']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <?php
                        $as = nl2br($rc->pod_data['fields']['labour_crews']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rc->pod_data['fields']['labour_crews']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->labour_crews == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['hold_osha_certification']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <?php
                        $as = nl2br($rc->pod_data['fields']['hold_osha_certification']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rc->pod_data['fields']['hold_osha_certification']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->hold_osha_certification == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['business_referrals']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <?php
                        $ri = pods( 'referral_information' );
                        ?>

                        <?php
                        if($contractor->display( 'business_referrals' )):
                            foreach ($contractor->field( 'business_referrals' , null, true  ) as $key => $ref): ?>
                                <ul class="well well-sm">
                                    <li class=""><strong>Name:</strong>  <?=$ref['ref_name']?> <a class="pull-right btn btn-xs btn-danger js-ref-delete" data-req="<?=$ref['id']?>">x</a></li>
                                    <li class=""><strong>Email:</strong>  <?=$ref['ref_email']?></li>
                                    <li class=""><strong>Phone Number:  </strong><?=$ref['ref_phone_number']?></li>
                                </ul>
                            <?php
                            endforeach;;
                        else:
                            echo "Not mentioned!";
                        endif;
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
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <a class="btn btn-info js-add-html pull-right">Add+</a>
                    </div>
                </div>


                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['business_information']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <?php
                        $as = nl2br($rc->pod_data['fields']['business_information']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rc->pod_data['fields']['business_information']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->business_information == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>



                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['interested_in_advertising']['label']?><span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rc->pod_data['fields']['interested_in_advertising']['name']?>" value="Yes" <?=$contractor->display('interested_in_advertising') == "Yes"?"checked":""?>> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rc->pod_data['fields']['interested_in_advertising']['name']?>" value="No" <?=$contractor->display('interested_in_advertising') == "No"?"checked":""?>> &nbsp;No
                            </label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="etype" value="own">
                <input type="hidden" name="redirect" value="<?=site_url('/dashboard/')."?menu_type=user&menu_slug=contractor_information&fed_nonce=" . wp_create_nonce( 'fed_nonce' )?>">


            <?php else:?>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['assign_roofing_request']['label']?></label>
                    <div class="col-sm-10">
                        <?php
                        $val = $contractor->field( 'assign_roofing_request', null, true );
                        $edata = [];
                        if($val):
                            foreach($val as $vkey => $v):
                                $edata[] = $v['id'];
                            endforeach;
                        endif;

                        $params = array(
                            'where' => ' t.status = "Request" ',
                            'limit'   => -1  // Return all rows
                        );

                        // Create and find in one shot
                        $rf_requests = pods( 'roofhub_request', $params );
                        ?>
                        <select data-placeholder="Choose requests..." class="chosen-select js-contractors" name="assign_roofing_request[]" tabindex="2" multiple>
                            <option value=""></option>
                            <?php if ( 0 < $rf_requests->total() ):
                                    $i = 1;
                                    while ( $rf_requests->fetch() ):?>
                                <option value="<?=$rf_requests->display('id')?>" <?=in_array($rf_requests->display('id'), $edata)?"selected":""?>><?=$rf_requests->display('name')?> (<?=$rf_requests->display('author')?> - <?=$rf_requests->display('state')?>)</option>
                            <?php endwhile;
                                endif;
                            ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['status']['label']?></label>
                    <div class="col-sm-10">
                        <?php
                        $as = nl2br($rc->pod_data['fields']['status']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        $val = $contractor->field( 'status', null, true );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?=$rc->pod_data['fields']['status']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$val==trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['featured_contractor']['label']?></label>
                    <div class="col-sm-10">
                        <?php $valf = $contractor->display( 'featured_contractor', null, true );?>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rc->pod_data['fields']['featured_contractor']['name']?>" value="Yes" <?=$valf=="Yes"?"checked":""?>> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rc->pod_data['fields']['featured_contractor']['name']?>" value="No" <?=$valf=="No"?"checked":""?>> &nbsp;No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?=$rc->pod_data['fields']['premium_contractor']['label']?></label>
                    <div class="col-sm-10">
                        <?php $val = $contractor->display( 'premium_contractor' , null, true );?>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rc->pod_data['fields']['premium_contractor']['name']?>" value="Yes" <?=$val=="Yes"?"checked":""?>> &nbsp;Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="<?=$rc->pod_data['fields']['premium_contractor']['name']?>" value="No" <?=$val=="No"?"checked":""?>> &nbsp;No
                            </label>
                        </div>
                    </div>
                </div>
            <?php endif;?>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-info" name="etf-hub-form-submit">Modify</button>
                </div>
            </div>
            <input type="hidden" name="fed_ajax_hook" value="global_data_update" />
            <input type="hidden" name="id" value="<?=encrypt_decrypt('encrypt',$id);?>">
            <input type="hidden" name="type" value="contractor">
            <?php wp_nonce_field('contractor-modify-nonce') ?>

            <div class="form-group">
                <div class="col-sm-8">
                    <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                    <div class="etf-community-ajax-feedback"></div>
                </div>
            </div>
        </form>
    <?php
    endwhile;
    ?>

    </div>
    </div>
    </div><?php

    $message = ob_get_contents();
    ob_end_clean();
    $data['html'] =  $message;
    echo json_encode($data);die;
}




/**
 * AJAX: Roofing view details html
 */
function fed_roofing_view_html(){
    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    $rc = pods( 'roofhub_request' );
    $data = [];
    ob_start();

    $params = array(
        'where'   => 't.id = '.$id,
        'limit'   => 1  // Return all rows
    );

    // Create and find in one shot
    $rrc = pods( 'roofhub_request', $params );
    $rrc_oth = pods( 'roofhub_request', $params );

    ?>
    <div class="row">
        <div class="col-md-12 text-right">
            <!--<a class="btn btn-warning js-edit-data" data-action="contractor_edit" data-req="<?/*=encrypt_decrypt('encrypt',$id)*/?>" href="javascript://" role="button" alt="Update" title="Update"><i class="fa fa-edit" aria-hidden="true"></i></a>--> <a class="btn btn-default js-close" href="javascript://" role="button" alt="Close" data-req="<?=encrypt_decrypt('encrypt',$id)?>" title="Close"><i class="fa fa-close" aria-hidden="true"></i></a>
        </div>
    </div>



    <ul class="nav nav-tabs">
        <li class="active">
            <a  href="#business" data-toggle="tab">Roofing Request Information</a>
        </li>
        <li><a href="#business-other" data-toggle="tab">Contact Information</a>
        </li>
    </ul>

    <div class="tab-content ">
        <div class="tab-pane well well-sm active" id="business">
            <h3>Information:</h3>

            <?php
            while ( $rrc->fetch() ):
                //$raw_data = $rrc->data();
                //$raw_data = $raw_data[0];print_r($raw_data);?>
                <dl>
                    <dt><?=$rc->pod_data['fields']['building_type']['label']?></dt>
                    <dd><?=$rrc->display( 'building_type' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['building_level']['label']?></dt>
                    <dd><?=$rrc->display( 'building_level' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['roof_type']['label']?></dt>
                    <dd><?=$rrc->display( 'roof_type' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['property_accessible']['label']?></dt>
                    <dd><?=$rrc->display( 'property_accessible' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['roof_accessible']['label']?></dt>
                    <dd><?=$rrc->display( 'roof_accessible' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['roof_cond']['label']?></dt>
                    <dd><?=$rrc->display( 'roof_cond' )?></dd>
                </dl>

                <?php if($rrc->display( 'roof_cond' ) == "Pitched Roof"):?>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['pitch_type']['label']?></dt>
                        <dd><?=$rrc->display( 'pitch_type' )?></dd>
                    </dl>
                <?php else:?>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['flat_type']['label']?></dt>
                        <dd><?=$rrc->display( 'flat_type' )?></dd>
                    </dl>
                <?php endif;?>

                <dl>
                    <dt><?=$rc->pod_data['fields']['visible_water_damage']['label']?></dt>
                    <dd><?=$rrc->display( 'visible_water_damage' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['any_skylights']['label']?></dt>
                    <dd><?=$rrc->display( 'any_skylights' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['any_satellite']['label']?></dt>
                    <dd><?=$rrc->display( 'any_satellite' )?></dd>
                </dl>


                <dl>
                    <dt><?=$rc->pod_data['fields']['visible_damage']['label']?></dt>
                    <dd><?=$rrc->display( 'visible_damage' )?></dd>
                </dl>

                <dl>
                    <dt><?=$rc->pod_data['fields']['insurance_claim']['label']?></dt>
                    <dd><?=$rrc->display( 'insurance_claim' )?></dd>
                </dl>
                <?php
                if($rrc->display( 'insurance_claim' ) == "Yes"):
                    ?>
                <div class="well well-lg">
                    <dl>
                        <dt><?=$rc->pod_data['fields']['insurance_provider_name']['label']?></dt>
                        <dd><?=$rrc->display( 'insurance_provider_name' )?></dd>
                    </dl>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['claims_agent_name']['label']?></dt>
                        <dd><?=$rrc->display( 'claims_agent_name' )?></dd>
                    </dl>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['claims_agents_phone_number']['label']?></dt>
                        <dd><?=$rrc->display( 'claims_agents_phone_number' )?></dd>
                    </dl>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['already_claimed']['label']?></dt>
                        <dd><?=$rrc->display( 'already_claimed' )?></dd>
                    </dl>
                </div>
                <?php
                endif;
                ?>
                <dl>
                    <dt><?=$rc->pod_data['fields']['time_frame_needed']['label']?></dt>
                    <dd><?=$rrc->display( 'time_frame_needed' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['pictures_to_upload']['label']?></dt>
                    <dd><?=$rrc->display( 'pictures_to_upload' )?></dd>
                </dl>
                <?php if($rrc->display( 'pictures_to_upload' ) == "Yes"):
                    $gallery = $rrc->field('roof_pictures', true);
                    ?>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['roof_pictures']['label']?></dt>
                        <dd>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    if(!isset($gallery[0])):
                                        $feat_image_url = wp_get_attachment_url( $gallery['ID'] );
                                        ?>
                                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                            <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="<?=$gallery['post_title']?>" data-caption="" data-image="<?=$feat_image_url?>" data-target="#image-gallery">
                                                <img class="img-responsive" src="<?=$feat_image_url?>" alt="<?=$gallery['post_title']?>"  style="height: 200px;">
                                            </a>
                                        </div>
                                    <?php
                                    else:
                                        foreach ($gallery as $pic):
                                            $feat_image_url = wp_get_attachment_url( $pic['ID'] );?>

                                            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                                <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="<?=$pic['post_title']?>" data-caption="" data-image="<?=$feat_image_url?>" data-target="#image-gallery">
                                                    <img class="img-responsive" src="<?=$feat_image_url?>" alt="<?=$pic['post_title']?>" style="height: 200px;">
                                                </a>
                                            </div>

                                        <?php endforeach;
                                    endif;
                                    ?>
                                </div>
                        </dd>
                    </dl>
                <?php endif;?>
                <dl>
                    <dt><?=$rc->pod_data['fields']['general_contractor']['label']?></dt>
                    <dd><?=$rrc->display( 'general_contractor' )?></dd>
                </dl>
                <?php if($rrc->display( 'general_contractor' ) == "Yes"):?>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['roof_elevations_information']['label']?></dt>
                        <dd><?=$rrc->display( 'roof_elevations_information' )?></dd>
                    </dl>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['roof_elevations_files']['label']?></dt>
                        <dd>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    $gallery = $rrc->field('roof_elevations_files', true);
                                    $feat_image_url = wp_get_attachment_url( $gallery['ID'] );?>
                                    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                        <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="<?=$gallery['post_title']?>" data-caption="" data-image="<?=$feat_image_url?>" data-target="#image-gallery">
                                            <img class="img-responsive" src="<?=$feat_image_url?>" alt="<?=$gallery['post_title']?>"  style="height: 200px;">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </dd>
                    </dl>
                <?php endif;
                if(!$rrc->display( 'assign_contractors' )):
                    $bc = $rrc->field('bidding_contractors', true);
                    ?>

                    <dl>
                        <dt><?=$rc->pod_data['fields']['bidding_contractors']['label']?></dt>
                        <dd>
                            <?php
                            if($bc):
                                echo "<ul>";
                                if(isset($bc[0])):
                                foreach($bc as $bkey => $b):
                                    $user = get_user_by('ID', $b['ID']);
                                    $name = get_user_meta($user->ID,"first_name", true)?get_user_meta($user->ID,"first_name", true)." ".get_user_meta($user->ID,"last_name", true):$user->user_email;
                                    if(is_super_admin()):
                                        $uid = $user->ID;
                                        $params = array(
                                            'where'   => 'author.ID = '.$uid,
                                            'limit'   => 1  // Return all rows
                                        );
                                        $contractor = pods( 'contractor', $params );
                                        $link = site_url("/dashboard/")."?menu_type=user&menu_slug=all_contractors&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=view&ctype=ac&rid=".encrypt_decrypt('encrypt',$contractor->display( 'id' ));
                                    else:
                                        $link = site_url("/dashboard/")."?menu_type=user&menu_slug=your_roofing_requests&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=viewcon&ctype=bd&cid=".encrypt_decrypt('encrypt',$user->ID);
                                    endif;
                                    echo "<li><a href='".$link."' class='btn btn-xs btn-success'>".$name."</a></li>";
                                endforeach;
                                else:
                                    $user = get_user_by('ID', $bc['ID']);
                                    $name = get_user_meta($user->ID,"first_name", true)?get_user_meta($user->ID,"first_name", true)." ".get_user_meta($user->ID,"last_name", true):$user->user_email;
                                    if(is_super_admin()):
                                        $uid = $user->ID;
                                        $params = array(
                                            'where'   => 'author.ID = '.$uid,
                                            'limit'   => 1  // Return all rows
                                        );
                                        $contractor = pods( 'contractor', $params );
                                        $link = site_url("/dashboard/")."?menu_type=user&menu_slug=all_contractors&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=view&ctype=ac&rid=".encrypt_decrypt('encrypt',$contractor->display( 'id' ));
                                    else:
                                        $link = site_url("/dashboard/")."?menu_type=user&menu_slug=your_roofing_requests&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=viewcon&ctype=bd&cid=".encrypt_decrypt('encrypt',$user->ID);
                                    endif;
                                    echo "<li><a href='".$link."' class='btn btn-xs btn-success'>".$name."</a></li>";
                                endif;
                                echo "</ul>";
                            endif;
                            ?>
                        </dd>
                    </dl>
                <?php
                else:
                    $ac = $rrc->field('assign_contractors', true);
                    ?>
                    <dl>
                        <dt><?=$rc->pod_data['fields']['assign_contractors']['label']?></dt>
                        <dd><?php
                            if($ac):
                                echo "<ul>";
                                foreach($ac as $akey => $a):
                                    $user = get_user_by('ID', $a['ID']);
                                    $name = get_user_meta($user->ID,"first_name", true)?get_user_meta($user->ID,"first_name", true)." ".get_user_meta($user->ID,"last_name", true):$user->user_email;

                                    if(is_super_admin()):
                                        $uid = $user->ID;
                                        $params = array(
                                            'where'   => 'author.ID = '.$uid,
                                            'limit'   => 1  // Return all rows
                                        );
                                        $contractor = pods( 'contractor', $params );
                                        $link = site_url("/dashboard/")."?menu_type=user&menu_slug=all_contractors&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=view&ctype=ac&rid=".encrypt_decrypt('encrypt',$contractor->display( 'id' ));
                                    else:
                                        $link = site_url("/dashboard/")."?menu_type=user&menu_slug=your_roofing_requests&fed_nonce=" . wp_create_nonce( 'fed_nonce' )."&display=viewcon&ctype=ac&cid=".encrypt_decrypt('encrypt',$user->ID);
                                    endif;
                                    echo "<li><a href='".$link."'  class='btn btn-xs btn-success'>".$name."</a></li>";
                                endforeach;
                                echo "</ul>";
                            endif;
                            ?>
                        </dd>
                    </dl>


                <?php
                endif;
            endwhile;
            ?>

        </div>
        <div class="tab-pane well well-sm" id="business-other">
            <h3>Contact Information:</h3>
            <?php
            while ( $rrc_oth->fetch() ):?>
                <dl>
                    <dt>Name</dt>
                    <dd><?=$rrc_oth->display( 'first_name' )?> <?=$rrc_oth->display( 'last_name' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['address']['label']?></dt>
                    <dd><?=$rrc_oth->display( 'address' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['city']['label']?></dt>
                    <dd><?=$rrc_oth->display( 'city')?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['state']['label']?></dt>
                    <dd><?=$rrc_oth->display( 'state' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['zip_code']['label']?></dt>
                    <dd><?=$rrc_oth->display( 'zip_code' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['email_address']['label']?></dt>
                    <dd><?=$rrc_oth->display( 'email_address' )?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['phone_number']['label']?></dt>
                    <dd><?=$rrc_oth->display( 'phone_number')?></dd>
                </dl>
                <dl>
                    <dt><?=$rc->pod_data['fields']['best_time_contact']['label']?></dt>
                    <dd><?=$rrc_oth->display( 'best_time_contact' )?></dd>
                </dl>

            <?php
                //print_r($rrc_oth);
            endwhile;
            ?>
        </div>



        <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="image-gallery-title"></h4>
                    </div>
                    <div class="modal-body">
                        <img id="image-gallery-image" class="img-responsive" src="">
                    </div>
                    <div class="modal-footer">

                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" id="show-previous-image">Previous</button>
                        </div>

                        <div class="col-md-8 text-justify" id="image-gallery-caption">
                            This text will be overwritten by jQuery
                        </div>

                        <div class="col-md-2">
                            <button type="button" id="show-next-image" class="btn btn-default">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div><?php

    $message = ob_get_contents();
    ob_end_clean();
    $data['html'] =  $message;
    echo json_encode($data);die;
}


/**
 * AJAX: Roofing edit & update html
 */
function fed_roofing_edit_html(){
    $id = $_POST['id'];
    $id = encrypt_decrypt('decrypt',$id);
    $rf = pods( 'roofhub_request' );
    $data = [];
    ob_start();

    $params = array(
        'where'   => 't.id = '.$id,
        'limit'   => 1  // Return all rows
    );

    // Create and find in one shot
    $rrq = pods( 'roofhub_request', $params );
    $raw_data = $rrq->data();
    $raw_data = $raw_data[0];

    //print_r($raw_data);
    ?>
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-info js-view-data" data-action="roofing_view" data-req="<?=encrypt_decrypt('encrypt',$id)?>" href="javascript://" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a> <a class="btn btn-default js-close" href="javascript://" role="button" alt="Close" data-req="<?=encrypt_decrypt('encrypt',$id)?>" title="Close"><i class="fa fa-close" aria-hidden="true"></i></a>
        </div>
    </div>

    <?php
    while ( $rrq->fetch() ):?>

        <ul class="nav nav-tabs">
            <li class="active">
                <a  href="#business" data-toggle="tab"><?=$rrq->display( 'first_name' )?> <?=$rrq->display( 'last_name' )?></a>
            </li>
        </ul>

        <div class="tab-content ">
        <div class="tab-pane well well-sm active" id="business">
        <form id="etf-hub-form"  action="<?php echo site_url( 'wp-load.php' );?>" class="form-horizontal" method="post">


            <?php /*?>
            <h3 class="fs-title">Roofing Request </h3>
            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['building_type']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <?php
                $as = nl2br($rf->pod_data['fields']['building_type']['options']['pick_custom']);
                $optdata = explode("<br />", $as );
                ?>
                <?php foreach ($optdata as $vtype):
                    $exp_vtype = explode("|",$vtype);
                    ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?=$rf->pod_data['fields']['building_type']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->building_type == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                        </label>
                    </div>
                <?php endforeach;?>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['building_level']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <?php
                $as = nl2br($rf->pod_data['fields']['building_level']['options']['pick_custom']);
                $optdata = explode("<br />", $as );
                ?>
                <?php foreach ($optdata as $vtype):
                    $exp_vtype = explode("|",$vtype);
                    ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?=$rf->pod_data['fields']['building_level']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->building_level == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                        </label>
                    </div>
                <?php endforeach;?>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['roof_type']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <?php
                $as = nl2br($rf->pod_data['fields']['roof_type']['options']['pick_custom']);
                $optdata = explode("<br />", $as );
                ?>
                <?php foreach ($optdata as $vtype):
                    $exp_vtype = explode("|",$vtype);
                    ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?=$rf->pod_data['fields']['roof_type']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->roof_type == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                        </label>
                    </div>
                <?php endforeach;?>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['property_accessible']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['property_accessible']['name']?>" value="Yes" <?=$rrq->display('property_accessible') == 'Yes'?"checked":""?>> &nbsp;Yes
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['property_accessible']['name']?>" value="No" <?=$rrq->display('property_accessible') == 'No'?"checked":""?>> &nbsp;No
                    </label>
                </div>
                </div>
            </div>


            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['roof_accessible']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <?php
                $as = nl2br($rf->pod_data['fields']['roof_accessible']['options']['pick_custom']);
                $optdata = explode("<br />", $as );
                ?>
                <?php foreach ($optdata as $vtype):
                    $exp_vtype = explode("|",$vtype);
                    ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?=$rf->pod_data['fields']['roof_accessible']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->roof_accessible == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                        </label>
                    </div>
                <?php endforeach;?>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['roof_cond']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <?php
                $as = nl2br($rf->pod_data['fields']['roof_cond']['options']['pick_custom']);
                $optdata = explode("<br />", $as );
                ?>
                <?php foreach ($optdata as $vtype):
                    $exp_vtype = explode("|",$vtype);
                    ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?=$rf->pod_data['fields']['roof_cond']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->roof_cond == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                        </label>
                    </div>
                <?php endforeach;?>
                </div>
            </div>


            <div class="form-group js-pitch-type" style="display: none;">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['pitch_type']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <?php
                $as = nl2br($rf->pod_data['fields']['pitch_type']['options']['pick_custom']);
                $optdata = explode("<br />", $as );
                ?>
                <?php foreach ($optdata as $vtype):
                    $exp_vtype = explode("|",$vtype);
                    ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?=$rf->pod_data['fields']['pitch_type']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=$raw_data->pitch_type == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                        </label>
                    </div>
                <?php endforeach;?>
                </div>
            </div>


            <div class="form-group js-flat-type" style="display: none;">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['flat_type']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <?php
                $as = nl2br($rf->pod_data['fields']['flat_type']['options']['pick_custom']);
                $optdata = explode("<br />", $as );
                ?>
                <?php foreach ($optdata as $vtype):
                    $exp_vtype = explode("|",$vtype);
                    ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?=$rf->pod_data['fields']['flat_type']['name']?>" value="<?=trim($exp_vtype[0])?>" <?=trim($exp_vtype[0])?>" <?=$raw_data->flat_type == trim($exp_vtype[0])?"checked":""?>> &nbsp;<?=trim($exp_vtype[1])?>
                        </label>
                    </div>
                <?php endforeach;?>
                </div>
            </div>


            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['visible_water_damage']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['visible_water_damage']['name']?>" value="Yes" <?=$rrq->display('visible_water_damage') == 'Yes'?"checked":""?>> &nbsp;Yes
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['visible_water_damage']['name']?>" value="No" <?=$rrq->display('visible_water_damage') == 'No'?"checked":""?>> &nbsp;No
                    </label>
                </div>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['any_skylights']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['any_skylights']['name']?>" value="Yes" <?=$rrq->display('any_skylights') == 'Yes'?"checked":""?>> &nbsp;Yes
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['any_skylights']['name']?>" value="No" <?=$rrq->display('any_skylights') == 'No'?"checked":""?>> &nbsp;No
                    </label>
                </div>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['any_satellite']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['any_satellite']['name']?>" value="Yes" <?=$rrq->display('any_satellite') == 'No'?"checked":""?>> &nbsp;Yes
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['any_satellite']['name']?>" value="No" <?=$rrq->display('any_satellite') == 'No'?"checked":""?>> &nbsp;No
                    </label>
                </div>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['visible_damage']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['visible_damage']['name']?>" value="Yes" <?=$rrq->display('visible_damage') == 'Yes'?"checked":""?>> &nbsp;Yes
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['visible_damage']['name']?>" value="No" <?=$rrq->display('visible_damage') == 'No'?"checked":""?>> &nbsp;No
                    </label>
                </div>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['insurance_claim']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['insurance_claim']['name']?>" value="Yes" <?=$rrq->display('insurance_claim') == 'Yes'?"checked":""?>> &nbsp;Yes
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['insurance_claim']['name']?>" value="No" <?=$rrq->display('insurance_claim') == 'No'?"checked":""?>> &nbsp;No
                    </label>
                </div>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['time_frame_needed']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
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
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['pictures_to_upload']['label']?></label>
                <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['pictures_to_upload']['name']?>" value="Yes" <?=$rrq->display('pictures_to_upload') == 'Yes'?"checked":""?>> &nbsp;Yes
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['pictures_to_upload']['name']?>" value="No" <?=$rrq->display('pictures_to_upload') == 'No'?"checked":""?>> &nbsp;No
                    </label>
                </div>
                </div>
            </div>

            <div class="form-group js-rf-pics" style="display: none;">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['roof_pictures']['label']?></label>
                <div class="col-sm-10">
                <div class="radio">
                    <input type="file" id="exampleInputFile" name="<?=$rf->pod_data['fields']['roof_pictures']['name']?>[]" multiple>
                </div>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['general_contractor']['label']?></label>
                <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['general_contractor']['name']?>" value="Yes" <?=$rrq->display('general_contractor') == 'No'?"checked":""?>> &nbsp;Yes
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?=$rf->pod_data['fields']['general_contractor']['name']?>" value="No" <?=$rrq->display('general_contractor') == 'No'?"checked":""?>> &nbsp;No
                    </label>
                </div>
                </div>
            </div>


            <div class="form-group js-elv-info" style="display:none;">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['roof_elevations_information']['label']?></label>
                <div class="col-sm-10">
                <div class="textarea">
                    <textarea name="<?=$rf->pod_data['fields']['roof_elevations_information']['name']?>"></textarea>
                </div>
                </div>
            </div>

            <div class="form-group js-elv-file" style="display: none;">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['roof_elevations_files']['label']?></label>
                <div class="col-sm-10">
                <div class="radio">
                    <input type="file" id="exampleInputFile" name="<?=$rf->pod_data['fields']['roof_elevations_files']['name']?>[]" multiple>
                </div>
                </div>
            </div>

            <?php
                $current_user = wp_get_current_user();
                $first_name = get_user_meta($current_user->ID, 'first_name', true);
                $last_name = get_user_meta($current_user->ID, 'last_name', true);
                $email_address = $current_user->user_email;
                $phone_number = get_user_meta($current_user->ID, 'phone_number', true);
                $address = get_user_meta($current_user->ID, 'address', true);
                $zip_code = get_user_meta($current_user->ID, 'zip_code', true);
            ?>

            <h3 class="fs-title">Personal Details</h3>
            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['first_name']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <input type="text" name="<?=$rf->pod_data['fields']['first_name']['name']?>" placeholder="<?=$rf->pod_data['fields']['first_name']['label']?>" value="<?=$first_name?>" />
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['last_name']['label']?></label>
                <div class="col-sm-10">
                <input type="text" name="<?=$rf->pod_data['fields']['last_name']['name']?>" placeholder="<?=$rf->pod_data['fields']['last_name']['label']?>"  value="<?=$last_name?>"/>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['email_address']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <input type="text" name="<?=$rf->pod_data['fields']['email_address']['name']?>" placeholder="<?=$rf->pod_data['fields']['email_address']['label']?>" value="<?=$email_address?>" <?=$email_address?"disabled":""?>/>
                <?php if($email_address):?>
                    <input type="hidden" name="<?=$rf->pod_data['fields']['email_address']['name']?>" value="<?=$email_address?>" />
                <?php endif;?>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['phone_number']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <input type="text" name="<?=$rf->pod_data['fields']['phone_number']['name']?>" placeholder="<?=$rf->pod_data['fields']['phone_number']['label']?>" value="<?=$phone_number?>" />
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['address']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <textarea name="<?=$rf->pod_data['fields']['address']['name']?>" placeholder="<?=$rf->pod_data['fields']['address']['label']?>"><?=$address?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['zip_code']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
                <input type="text" name="<?=$rf->pod_data['fields']['zip_code']['name']?>" placeholder="<?=$rf->pod_data['fields']['zip_code']['label']?>" value="<?=$zip_code?>" />
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="col-sm-2 control-label"><?=$rf->pod_data['fields']['best_time_contact']['label']?><span style="color: red;">*</span></label>
                <div class="col-sm-10">
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
            </div>

            <?php */?>


            <?php
            $bc = [];
            if($rrq->display( 'bidding_contractors' )):
                foreach ($rrq->field( 'bidding_contractors' , null, true  ) as $key => $ref):
                    $bc[] = $ref['ID'];
                endforeach;
            endif;

            $ac = [];
            if($rrq->display( 'assign_contractors' )):
                foreach ($rrq->field( 'assign_contractors' , null, true  ) as $key => $ref):
                    $ac[] = $ref['ID'];
                endforeach;
            endif;
            ?>

            <div class="form-group">

                <div class="col-sm-12">
                    <small>If you assign a contractor then the bidding contractors will not assign or existing assigned bidding contractors will removed from list & status will automatically assigned as "Assign".  </small><br>
                    <small>If you assign bidding contractors then status will automatically assigned as "Bidding".  </small>
                </div>
            </div>
            <div class="form-group">
                <label for="state" class="col-sm-2 control-label"> Assign <?=$rf->pod_data['fields']['bidding_contractors']['label']?> </label>
                <div class="col-sm-10">
                    <select data-placeholder="Choose contractors..." class="chosen-select js-contractors" name="bidding_contractors[]" tabindex="2" multiple>
                        <option value="">Select</option>
                        <?php foreach (get_users_by_state_city() as $skey => $sobj):?>
                            <option value="<?=$skey?>" data-id="<?=$skey?>" <?=in_array($skey, $bc)?"selected":""?> ><?=$sobj?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label for="state" class="col-sm-2 control-label"> <?=$rf->pod_data['fields']['assign_contractors']['label']?> </label>
                <div class="col-sm-10">
                    <select data-placeholder="Choose contractors..." class="chosen-select js-contractors" name="assign_contractors" tabindex="-1">
                        <option value=""></option>
                        <?php foreach (get_users_by_state_city() as $skey => $sobj):?>
                            <option value="<?=$skey?>" data-id="<?=$skey?>" <?=in_array($skey, $ac)?"selected":""?>><?=$sobj?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label for="state" class="col-sm-2 control-label"> <?=$rf->pod_data['fields']['status']['label']?> </label><span style="color: red;">*</span>
                <div class="col-sm-10">
                    <select name="status" class="form-control">
                        <option value=""> Select status</option>
                        <?php
                        $as = nl2br($rf->pod_data['fields']['status']['options']['pick_custom']);
                        $optdata = explode("<br />", $as );
                        ?>
                        <?php foreach ($optdata as $vtype):
                            $exp_vtype = explode("|",$vtype);
                            ?>
                            <option value="<?=trim($exp_vtype[0])?>" <?=$raw_data->status == trim($exp_vtype[0])?"selected":""?>><?=trim($exp_vtype[1])?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-info" name="etf-hub-form-submit">Modify</button>
                </div>
            </div>
            <input type="hidden" name="fed_ajax_hook" value="global_data_update" />
            <input type="hidden" name="id" value="<?=encrypt_decrypt('encrypt',$id);?>">
            <input type="hidden" name="type" value="roofhub_request">
            <?php wp_nonce_field('rrq-modify-nonce') ?>

            <div class="form-group">
                <div class="col-sm-8">
                    <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                    <div class="etf-community-ajax-feedback"></div>
                </div>
            </div>
        </form>
    <?php
    endwhile;
    ?>

    </div>
    </div>
    </div><?php

    $message = ob_get_contents();
    ob_end_clean();
    $data['html'] =  $message;
    echo json_encode($data);die;
}