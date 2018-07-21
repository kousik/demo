<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 29/5/18
 * Time: 8:52 PM
 */


$userinfo = wp_get_current_user();

    if ( current_user_can( 'manage_options' ) ):
?>
<h3>Send global E-mail:</h3>
<form id="etf-hub-form" name="etf-community-form" class="email form-horizontal" action="<?php echo site_url( 'wp-load.php' );?>" method="post">

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">E-mail Group </label>
        <div class="col-sm-10">
            <select class="form-control" name="to">
                <option value="">Select Group</option>
                <option value="Customer">All Customers</option>
                <option value="Agent">All Agents</option>
                <option value="Distributor">All Distributors</option>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Subject</label>
        <div class="col-sm-10">
            <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject" value="">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Message</label>
        <div class="col-sm-10">
            <textarea name="message" id="message" class="form-control"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="etf-hub-form-submit" class="btn btn-warning">Send</button>
        </div>
    </div>
    <input type="hidden" name="fed_ajax_hook" value="send_global_email" />
    <input type="hidden" name="type" value="admin" />
    <input type="hidden" name="noreset" value="">
    <?php wp_nonce_field('global-email-nonce') ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
            <div class="etf-community-ajax-feedback"></div>
        </div>
    </div>
</form>
<?php
    else:
 ?>
        <h3>ASK A QUESTION:</h3>
        <form id="etf-hub-form" name="etf-community-form" class="email form-horizontal" action="<?php echo site_url( 'wp-load.php' );?>" method="post">

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Question</label>
                <div class="col-sm-10">
                    <input type="text" name="subject" class="form-control" id="subject" placeholder="Question" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <textarea name="message" id="message" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="etf-hub-form-submit" class="btn btn-warning">Send</button>
                </div>
            </div>
            <input type="hidden" name="fed_ajax_hook" value="send_email_to_admin" />
            <input type="hidden" name="noreset" value="">
            <?php wp_nonce_field('user-email-nonce') ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                    <div class="etf-community-ajax-feedback"></div>
                </div>
            </div>
        </form>

<?php
    endif;
 ?>
