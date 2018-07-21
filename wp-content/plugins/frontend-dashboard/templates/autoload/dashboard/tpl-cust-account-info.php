<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 29/5/18
 * Time: 8:52 PM
 */


$userinfo = wp_get_current_user();
$edit_style = 'style="display:none;"';
if (isset($_GET['display']) && $_GET['display'] == 'custedit'):
    if (isset($_GET['rid']) && $_GET['rid']):
        $style = 'style="display:none;"';
        $edit_style = "";
    endif;
endif;
?>
<div class="cust-view" <?=$style?>>
    <?php
    $uid = $userinfo->ID;
        $user = get_user_by('ID', $uid);
        $edit_link = site_url("/dashboard/")."?menu_type=user&menu_slug=account_info_cust&&fed_nonce=". wp_create_nonce( 'fed_nonce' )."&display=custedit&rid=".encrypt_decrypt('encrypt',$user->ID);
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
                                    <td><a href="javascript://"><?=$a_log?></a></td>
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
                    <!--<a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>-->
                    <a>&nbsp;</a>
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


<div class="cust-edit" <?=$edit_style?>>
    <?php
    $uid = encrypt_decrypt('decrypt', $_GET['rid']);
    $user = get_user_by('ID', $uid);
    ?>
    <h2>Edit Account</h2>
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
            <label for="inputEmail3" class="col-sm-2 control-label">Gender</label>
            <div class="col-sm-5">
                <input type="text" name="gender" class="form-control" id="gender" placeholder="Gender" value="<?=get_user_meta($user->ID, 'gender', true)?>" maxlength="1">
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">D.O.B.</label>
            <div class="col-sm-5">
                <input type="text" name="dob" class="form-control date_picker" id="dob" placeholder="DOB" value="<?=get_user_meta($user->ID, 'dob', true)?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Mobile Number</label>
            <div class="col-sm-5">
                <input type="text" name="mobile_number" class="form-control" id="mobile_number" placeholder="Mobile Number" value="<?=get_user_meta($user->ID, 'mobile_number', true)?>" disabled>
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
            <div class="col-sm-offset-2 col-sm-5">
                <button type="submit" class="btn btn-info pull-right" name="etf-hub-form-submit">Submit</button>
            </div>
        </div>

        <div class="clear"></div>
        <input type="hidden" name="fed_ajax_hook" value="user_data_update" />
        <input type="hidden" name="type" value="customer" />
        <input type="hidden" name="id" value="<?=$_GET['rid']?>">
        <?php
            $link = site_url("/dashboard/")."?menu_type=user&menu_slug=account_info_cust&fed_nonce=" . wp_create_nonce( 'fed_nonce' );
        ?>
        <input type="hidden" name="redirect" value="<?=$link?>">
        <?php wp_nonce_field('userupdate-update-nonce') ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                <div class="etf-community-ajax-feedback"></div>
            </div>
        </div>

    </form>
</div>