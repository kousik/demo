<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 29/5/18
 * Time: 8:52 PM
 */


$userinfo = wp_get_current_user();
?>
<h3>Update E-mail/Login ID:</h3>
<form id="etf-hub-form" name="etf-community-form" class="email form-horizontal" action="<?php echo site_url( 'wp-load.php' );?>" method="post">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">E-mail</label>
        <div class="col-sm-10">
            <input type="email" name="user_email" class="form-control" id="user_email" placeholder="Email" value="<?=$userinfo->user_email?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="etf-hub-form-submit" class="btn btn-warning">Update</button>
        </div>
    </div>
    <input type="hidden" name="fed_ajax_hook" value="update_email" />
    <input type="hidden" name="noreset" value="true">
    <?php wp_nonce_field('update-email-nonce') ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
            <div class="etf-community-ajax-feedback"></div>
        </div>
    </div>
</form>

<h3>Update Password:</h3>
<form id="etf-hub-form" name="etf-community-form" class="password form-horizontal" action="<?php echo site_url( 'wp-load.php' );?>" method="post">
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10">
            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Confirm Password</label>
        <div class="col-sm-10">
            <input type="password" name="conf_password" class="form-control" id="cpassword" placeholder="Password">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="etf-hub-form-submit" class="btn btn-warning">Update Password</button>
        </div>
    </div>
    <input type="hidden" name="fed_ajax_hook" value="update_password" />
    <?php wp_nonce_field('update-password-nonce') ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
            <div class="etf-community-ajax-feedback"></div>
        </div>
    </div>
</form>
