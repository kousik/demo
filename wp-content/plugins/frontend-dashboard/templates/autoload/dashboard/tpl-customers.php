<?php
$style = "";
$view_style = 'style="display:none;"';
if(isset($_GET['display']) && $_GET['display'] == 'custview'):
    if(isset($_GET['rid']) && $_GET['rid']):
        $style = 'style="display:none;"';
        $view_style = "";
    endif;
endif;

$edit_style = 'style="display:none;"';
if (isset($_GET['display']) && $_GET['display'] == 'custedit'):
    if (isset($_GET['rid']) && $_GET['rid']):
        $style = 'style="display:none;"';
        $edit_style = "";
    endif;
endif;

?>
<div class="customers-list" <?=$style?>>
    <!--<div class="row">
        <div class="col-md-12">
            <div class="response" style="display: none;"></div>
            <button  class="bth btn-sm btn-success pull-right" data-toggle="modal" data-target="#agentForm"><i class="fa fa-user-circle"></i> Add Agents</button>
        </div>
    </div>-->
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive js-table-content">
                <table class="table table-hover"  id="customer_table">
                    <thead>
                    <tr id="filters">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Customer ID</th>
                        <th>Password</th>
                        <th>Name</th>
                        <th>Assigned Agent</th>
                        <th>Phone</th>
                        <th>State</th>
                        <th>City</th>
                        <th>PIN</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="customer-view" <?=$view_style?>>
    <?php
    $uid = encrypt_decrypt('decrypt', $_GET['rid']);
    $user = get_user_by('ID', $uid);
    $edit_link = site_url("/dashboard/")."?menu_type=user&menu_slug=customers&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=custedit&rid=".encrypt_decrypt('encrypt',$user->ID);
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-2 col-lg-offset-1 toppad" >


            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=get_user_meta($uid, 'first_name', true)?></h3>
                </div>
                <div class="panel-body">
                    <div class="row" id="printarea">
                        <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="<?=no_image_url()?>" class="img-circle img-responsive"> </div>
                        <div class=" col-md-9 col-lg-9 ">
                            <h3>Personal Information:</h3>
                            <table class="table table-user-information">
                                <tbody>
                                <tr>
                                    <td>Name:</td>
                                    <td><?=get_user_meta($uid, 'first_name', true)?></td>
                                </tr>
                                <tr>
                                    <td>User ID:</td>
                                    <td><?=$user->user_login?></td>
                                </tr>
                                <tr>
                                    <td>Login Password:</td>
                                    <td><?=get_user_meta($uid, 'pwd', true)?encrypt_decrypt('decrypt',get_user_meta($uid, 'pwd', true)):"*******"?></td>
                                </tr>
                                <tr>
                                    <td>E-mail:</td>
                                    <td><a href="mailto:<?=$user->user_email?>"><?=$user->user_email?></a></td>
                                </tr>
                                <?php
                                $ag_id = get_user_meta($uid, 'agent_id', true)?get_user_meta($uid, 'agent_id', true):false;
                                if($ag_id):
                                    $agt = get_user_by("login", $ag_id);
                                    $a_log = $agt->user_login;
                                    $ag_link = site_url("/dashboard/")."?menu_type=user&menu_slug=agents&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=agview&rid=".encrypt_decrypt('encrypt',$agt->ID);
                                else:
                                    $a_log = "N/A";
                                    $ag_link = "javascript://";
                                endif;
                                ?>
                                <tr>
                                    <td>Assigned Agent:</td>
                                    <td><a href="<?=$ag_link?>"><?=$a_log?></a></td>
                                </tr>

                                <tr>
                                    <td>Registered Date:</td>
                                    <td><?=$user->user_registered?></td>
                                </tr>

                                <tr>
                                    <td>Gender:</td>
                                    <td><?= get_user_meta($uid, 'gender', true) ? get_user_meta($uid, 'gender', true) : "N/A" ?></td>
                                </tr>

                                <tr>
                                    <td>DOB:</td>
                                    <td><?= get_user_meta($uid, 'dob', true) ? get_user_meta($uid, 'dob', true) : "N/A" ?></td>
                                </tr>

                                <tr>
                                    <td>UUID:</td>
                                    <td><?= get_user_meta($uid, 'adddr_ui', true) ? encrypt_decrypt("decrypt",get_user_meta($uid, 'adddr_ui', true)) : "******" ?>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <h3>Contact Information:</h3>
                            <table class="table table-user-information">
                                <tbody>
                                <tr>
                                    <td>Phone Number</td>
                                    <td><?= get_user_meta($uid, 'mobile_number', true) ? get_user_meta($uid, 'mobile_number', true) : "N/A" ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>
                                        <address>
                                            <?=get_user_meta($uid, 'address1', true)?get_user_meta($uid, 'address1', true).",<br>":""?>
                                            <?=get_user_meta($uid, 'address2', true)?get_user_meta($uid, 'address2', true).",<br>":""?>
                                            <?=get_user_meta($uid, 'state', true)?get_user_meta($uid, 'state', true).",<br>":""?>
                                            <?=get_user_meta($uid, 'city', true)?get_user_meta($uid, 'city', true).", ":""?>
                                            <?=get_user_meta($uid, 'pin', true)?" <strong>PIN :</strong>".get_user_meta($uid, 'pin', true):""?>
                                        </address>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <h3>Device Information:</h3>
                            <table class="table table-user-information">
                                <tbody>
                                <tr>
                                    <td>IEMI:</td>
                                    <td><?=get_user_meta($uid, 'iemi', true)?date('d M, Y', strtotime(get_user_meta($uid, 'iemi', true)) ):"N/A"?></td>
                                </tr>
                                <tr>
                                    <td>MAKE:</td>
                                    <td><?=get_user_meta($uid, 'make', true)?date('d M, Y', strtotime(get_user_meta($uid, 'make', true)) ):"N/A"?></td>
                                </tr>
                                <tr>
                                    <td>MODEL</td>
                                    <td><?=get_user_meta($uid, 'model', true)?get_user_meta($uid, 'model', true):"N/A"?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <a data-original-title="Broadcast Message" data-toggle="modal" data-target="#mailleModal" data-whatever="@fat" type="button" class="btn btn-sm btn-primary" ><i class="glyphicon glyphicon-envelope"></i></a>
                    <a id="btn-print" data-original-title="Print" data-head="<?=get_user_meta($uid, 'first_name', true)?>" type="button" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-print"></i></a>
                    <span class="pull-right">
                            <a href="<?=$edit_link?>" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                            <!--<a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger js-request-delete" data-redirect="yes" data-url="<?/*=site_url("/dashboard/")."?menu_type=user&menu_slug=agents&&fed_nonce=". wp_create_nonce( 'fed_nonce' )*/?>" data-req="<?/*=encrypt_decrypt('encrypt',$user->ID)*/?>"><i class="glyphicon glyphicon-remove"></i></a>-->
                        <a data-original-title="Close Window" class="btn btn-sm btn-danger" title="Close Window" onclick="goBack()"><i class="glyphicon glyphicon-remove"></i></a>
                        </span>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="customer-edit" <?=$edit_style?>>
    <?php
    $uid = encrypt_decrypt('decrypt', $_GET['rid']);
    $user = get_user_by('ID', $uid);
    ?>
    <h2>Edit Customer : [ <?=$user->user_login?> ]</h2>
    <form class="etf-hub-form-agent form-horizontal" id="etf-hub-form-agent" action="<?php echo site_url( 'wp-load.php' );?>" name="etf-community-form" enctype="multipart/form-data" method="post">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-5">
                <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Name" value="<?=get_user_meta($user->ID, 'first_name', true)?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-5">
                <input type="email" name="user_email" class="form-control" id="inputEmail3" placeholder="Email" value="<?=$user->user_email?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-5">
                <?php
                $pwd = get_user_meta($user->ID, 'pwd', true)?encrypt_decrypt('decrypt', get_user_meta($user->ID, 'pwd', true)):"";
                ?>
                <input type="text" name="user_pass" class="form-control" id="inputPassword3" placeholder="Password" value="<?=$pwd?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Assigned Agents</label>
            <div class="col-sm-5">
                <?php
                $ag_id = get_user_meta($user->ID, 'agent_id', true);
                ?>
                <select data-placeholder="select a Agent..." class="chosen-select js-chosen form-control" tabindex="2" name="agent_id">
                    <option value=""></option>
                    <?php
                    $agents = isms_get_users_by_role('agent');
                    if($agents):
                        foreach ($agents as $agt):
                            echo  '<option value="'.$agt->user_login.'" ' . selected($ag_id, $agt->user_login, false).'>'.$agt->user_login.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Mobile Number</label>
            <div class="col-sm-5">
                <input type="number" maxlength="10" name="mobile_number" class="form-control" id="mobile_number" placeholder="Mobile Number" value="<?=get_user_meta($user->ID, 'mobile_number', true)?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Address 1</label>
            <div class="col-sm-5">
                <textarea  name="address1" class="form-control" id="address1" placeholder="Address 1"><?=get_user_meta($user->ID, 'address1', true)?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Address 2</label>
            <div class="col-sm-5">
                <textarea  name="address2" class="form-control" id="address2" placeholder="Address 2"><?=get_user_meta($user->ID, 'address2', true)?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">State</label>
            <div class="col-sm-5">
                <?php
                $state = get_user_meta($user->ID, 'state', true);
                $city = get_user_meta($user->ID, 'city', true);
                $cities = fdb_get_city($state);
                ?>
                <select data-placeholder="Choose a State..." class="chosen-select js-state form-control" name="state" tabindex="2" required>
                    <option value=""></option>
                    <?php foreach (fdb_get_state() as $skey => $sobj):?>
                        <option value="<?=$sobj->name?>" <?=$state==$sobj->name?'selected':''?>><?=$sobj->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">City</label>
            <div class="col-sm-5">
                <select data-placeholder="Choose a City..." class="chosen-select js-city" name="city" tabindex="2" style="width: 100% !important;">
                    <option value=""></option>
                    <?php
                    //if($cities):
                        foreach ($cities as $ckey => $cobj):
                            echo  '<option value="'.$cobj->name.'" ' . selected($city, $cobj->name, false).'>'.$cobj->name.'</option>';
                        endforeach;
                    //endif;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">PIN code</label>
            <div class="col-sm-5">
                <input type="number" pattern="\d*" maxlength="6" name="pin" class="form-control" id="pin" placeholder="PIN Code" value="<?=get_user_meta($user->ID, 'pin', true)?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Status</label>
            <div class="col-sm-5">
                <select class="form-control" name="user_status">
                    <option value="0" <?=$user->user_status==0?"selected":""?>>Active</option>
                    <option value="1" <?=$user->user_status==1?"selected":""?>>De-Active</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <button type="submit" class="btn btn-info pull-right" name="etf-hub-form-submit">Submit</button>
            </div>
        </div>

        <div class="clear"></div>
        <input type="hidden" name="fed_ajax_hook" value="customer_data_update" />
        <input type="hidden" name="id" value="<?=$_GET['rid']?>">
        <?php
        $link = site_url("/dashboard/")."?menu_type=user&menu_slug=customers&fed_nonce=" . wp_create_nonce( 'fed_nonce' );
        ?>
        <input type="hidden" name="redirect" value="<?=$link?>">
        <?php wp_nonce_field('customer-update-nonce') ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                <div class="etf-community-ajax-feedback"></div>
            </div>
        </div>

    </form>
</div>


<div class="modal fade" id="mailleModal" tabindex="-1" role="dialog" aria-labelledby="mailleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">New message</h4>
            </div>
            <form class="etf-hub-form-mail" id="etf-hub-form-mail" action="<?php echo site_url( 'wp-load.php' );?>" name="etf-community-form" method="post">
            <div class="modal-body message-body">
                
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Recipient:</label>
                        <input type="text" class="form-control" id="recipient-name" name="to" value="<?=$user->user_email?>">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Subject:</label>
                        <input type="text" class="form-control" id="recipient-sub" name="subject">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea class="form-control" id="message-text" name="message"></textarea>
                    </div>

                <input type="hidden" name="fed_ajax_hook" value="admin_to_user_mail" />
                <?php wp_nonce_field('mail-send-nonce') ?>
                <input type="hidden" name="user_name" value="<?=get_user_meta($uid, 'first_name', true)?>" />
                <div class="form-group">
                        <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                        <div class="etf-community-ajax-feedback"></div>
                </div>
                
            </div>
            <div class="modal-footer message-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="etf-hub-form-submit">Send message</button>
            </div>
            </form>
        </div>
    </div>
</div>