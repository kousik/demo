<?php
/**
 * Forgot Password only
 */

$details = fed_forgot_password_only();

do_action( 'fed_before_forgot_password_only_form' );
?>

    <div class="row" style="padding-top: 7%; padding-bottom: 23.5%;">
        <div class="col-md-6 col-md-offset-3">

            <div class="bc_fed container fed_login_container">
                <?php echo fed_loader(); ?>
                <div class="row">
                    <div class="col-xs-12 col-md-10" style="padding-left: 0px;">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center" ><?php echo $details['menu']['name']; ?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="fed_tab_content"
                                     data-id="<?php echo $details['menu']['id'] ?>">
                                    <form method="post"
                                          class="fed_form_post"
                                    >
                                        <?php
                                        $contents = $details['content'];
                                        uasort( $contents, 'fed_sort_by_order' );
                                        foreach ( $contents as $content ) {
                                            ?>
                                            <div class="form-group">
                                                <label><?php echo $content['name'] ?></label>
                                                <?php echo $content['input'] ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7">
                                                <input type="hidden"
                                                       name="submit"
                                                       value="Forgot Password"/>
                                                <button class="btn btn-primary" type="submit"><?php echo $details['button'] ?></button>
                                            </div>
                                        </div>
                                        <?php
                                        $redirect = '';
                                        if(isset($_GET['redirect_to']) && $_GET['redirect_to']){
                                            $redirect = $_GET['redirect_to'];
                                        }
                                        ?>
                                        <input type="hidden" name="redirect_to" value="<?=$redirect?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
do_action( 'fed_after_forgot_password_only_form' );