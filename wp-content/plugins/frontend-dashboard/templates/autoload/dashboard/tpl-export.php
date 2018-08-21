<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 29/5/18
 * Time: 8:52 PM
 */


$userinfo = wp_get_current_user();

    if ( current_user_can( 'edit_pages' ) ):
?>
        <h3>Export Customer Data:</h3>
        <form id="etf-hub-form" name="etf-community-form" class="ag-export form-inline" action="<?php echo site_url( 'wp-load.php' );?>" method="post">
            <div class="form-group">
                <label for="exampleInputName2">By Agent:</label>
                <select data-placeholder="Choose a From Agent..." class="chosen-select js-chosen form-control" name="agent_id" tabindex="2" style="width: 100% !important;">
                    <option value=""></option>
                    <?php
                    $agents = isms_get_users_by_role($role = "Agent");
                    if($agents):
                        foreach ($agents as $user):
                            echo  '<option value="'.$user->user_login.'" >'.$user->user_login.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <input type="hidden" name="fed_ajax_hook" value="export_customer" />
            <input type="hidden" name="type" value="agent" />
            <input type="hidden" name="noreset" value="">
            <?php wp_nonce_field('export-nonce') ?>
            <button type="submit" class="btn btn-info" name="etf-hub-form-submit">Export</button>
            <div class="form-group">
                <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                <div class="etf-community-ajax-feedback"></div>
            </div>
        </form>
    <hr>

        <form id="etf-hub-form" name="etf-community-form" class="cst-export form-inline" action="<?php echo site_url( 'wp-load.php' );?>" method="post">
            <div class="form-group">
                <label for="exampleInputName2">Select Options:</label>
                <select class="form-control" name="group">
                    <option value="all">All Users</option>
                    <option value="active">Active Users</option>
                    <option value="deactive">De-Active Users</option>
                </select>
            </div>

            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail3">From date</label>
                <input type="text" name="from" class="form-control date_picker" id="date_picker" placeholder="From date">
            </div>

            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail3">To Date</label>
                <input type="text" name="to" class="form-control date_picker_s" id="date_picker_s" placeholder="To Date">
            </div>
            <input type="hidden" name="fed_ajax_hook" value="export_customer" />
            <input type="hidden" name="type" value="custom" />
            <input type="hidden" name="noreset" value="">
            <?php wp_nonce_field('export-nonce') ?>
            <button type="submit" class="btn btn-info" name="etf-hub-form-submit">Export</button>
            <div class="form-group">
                <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                <div class="etf-community-ajax-feedback"></div>
            </div>
        </form>
<?php
    endif;
 ?>
