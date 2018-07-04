<div class="row">
    <div class="col-md-12">
        <div class="response" style="display: none;"></div>
        <button  class="bth btn-sm btn-success pull-right" data-toggle="modal" data-target="#distForm"><i class="fa fa-user-circle"></i> Add Distributors</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive js-table-content">
            <table class="table table-hover"> <thead>
                <tr>
                    <th>#</th>
                    <th>Distributor ID</th>
                    <th>Password</th>
                    <th>Name</th>
                    <th>Total Agents</th>
                    <th>Total Leads by Agents</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if($users =isms_get_users_by_role($role = "Distributor")):
                    $i = 1;
                    foreach ($users as $user):
                ?>
                        <tr class="row-<?=encrypt_decrypt('encrypt', $user->ID)?>">
                            <td><?=$i?></td>
                            <td><?=$user->user_login?></td>
                            <td><?=encrypt_decrypt('decrypt', get_user_meta($user->ID, 'pwd', true))?></td>
                            <td><?=get_user_meta($user->ID, 'first_name', true)?get_user_meta($user->ID, 'first_name', true):"--"?></td>
                            <td><?=isms_get_total_agents_by_distributor($user->ID)?></td>
                            <td><?=isms_get_total_leads_by_agents_single_distributor($user->ID)?></td>

                            <td><?=$user->user_status == 1?'<span class="label label-danger">De-Active</span>':'<span class="label label-success">Active</span>'?></td>
                            <td>
                                <a class="btn btn-info btn-xs js-view-data" data-action="user_view" data-req="<?=encrypt_decrypt('encrypt',$user->ID)?>" href="javascript://" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn btn-warning btn-xs js-edit-data" data-action="user_edit" data-req="<?=encrypt_decrypt('encrypt',$user->ID)?>" href="javascript://" role="button" alt="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a class="btn btn-danger btn-xs js-request-delete" href="javascript://" role="button" alt="Delete" data-req="<?=encrypt_decrypt('encrypt',$user->ID)?>" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                <?php
                        $i++;
                    endforeach;
                else:
                ?>
                    <tr>
                        <th scope="row" colspan="10">Sorry! No data found!</th>
                    </tr>
                <?php
                endif;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="js-content-body" style="display: none;">

</div>


<div class="modal fade" id="distForm" tabindex="-1" role="dialog" aria-labelledby="distFormLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Add Bulk Distributors</h4>
            </div>
            <div class="modal-body">
                <form id="etf-hub-form"  action="<?php echo site_url( 'wp-load.php' );?>" method="post">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Number of Distributors:</label>
                        <input type="number" class="form-control" id="dist_number" name="dist_number" value="1">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Number of New Agents:</label>
                        <input type="number" class="form-control" id="agent_number" name="agent_number" value="10">
                        <small>(New Agents will create for each distributors and automatically assign created new distributors )</small>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Number of Target Leads:</label>
                        <input type="number" class="form-control" id="leads_number" name="leads_number" value="50">
                    </div>


                    <div class="form-group">
                        <label for="state" class="control-label"> State </label>

                            <select data-placeholder="Choose a State..." class="chosen-select js-profile-state" name="state" tabindex="2" style="width: 100% !important;" required>
                                <option value=""></option>
                                <?php foreach (fdb_get_state() as $skey => $sobj):?>
                                    <option value="<?=$sobj->name?>" data-id="<?=$sobj->id?>" <?=$raw_data->state == $sobj->name?"selected":""?>><?=$sobj->name?></option>
                                <?php endforeach;?>
                            </select>
                    </div>

                    <div class="form-group" style="display: none;">
                        <label for="city" class="control-label"> City </label>
                            <select data-placeholder="Choose a City..." class="chosen-select js-profile-city" name="city" tabindex="2">
                                <?php foreach (fdb_get_city($raw_data->state) as $ckey => $cobj):?>
                                    <option value="<?=$cobj->name?>" data-id="<?=$cobj->id?>" <?=$raw_data->city == $cobj->name?"selected":""?>><?=$cobj->name?></option>
                                <?php endforeach;?>
                            </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-warning pull-right" name="etf-hub-form-submit">Submit</button>
                    </div>

                    <div class="clear"></div>
                    <input type="hidden" name="fed_ajax_hook" value="global_data_update" />
                    <input type="hidden" name="type" value="distributor">
                    <?php
                    $link = site_url("/dashboard/")."?menu_type=user&menu_slug=distributors&fed_nonce=" . wp_create_nonce( 'fed_nonce' );
                    ?>
                    <input type="hidden" name="redirect" value="<?=$link?>">
                    <?php wp_nonce_field('agent-nonce') ?>

                    <div class="form-group">
                        <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                        <div class="etf-community-ajax-feedback"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>