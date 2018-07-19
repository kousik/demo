<?php
/**
 * Logged in User form
 */

$details = fed_login_only();

do_action( 'fed_before_login_only_form' );
?>
    <div class="row" style="padding-top: 5%; padding-bottom: 16.5%;">
        <div class="col-md-6 col-md-offset-3">

            <!-- Tab panes -->

                    <div class="bc_fed container fed_login_container">
                        <?php echo fed_loader(); ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-10" style="padding-left: 0px;">
                                <div class="panel panel-primary" style="border-radius: 0px;">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?php echo $details['menu']['name']; ?></h3>
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
                                                        <?php echo ! empty( $content['name'] ) ? '<label>' . $content['name'] . '</label>' : ''; ?><?php echo $content['input'] ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <input type="hidden" name="submit" value="Login"/>
                                                        <button class="btn btn-primary" type="submit"><?php echo $details['button'] ?></button>
                                                    </div>
                                                    <div class="col-md-12 padd_top_20 text-center">
                                                        <?php /*if ( $reg = fed_get_registration_url() ) {
                                                            $redirect = '';
                                                            if(isset($_GET['redirect_to']) && $_GET['redirect_to']){
                                                                $redirect = '?redirect_to='.$_GET['redirect_to'];
                                                            }
                                                            */?><!--
                                                            <a href="<?php /*echo $reg.$redirect; */?>" style="margin-right: 20px;">
                                                                <?php /*_e( 'Don\'t have an account? Sign Up Now', 'frontend-dashboard' ) */?>
                                                            </a>

                                                            --><?php
/*                                                        } */?>
                                                        <?php if ( $forgot = fed_get_forgot_password_url() ) {
                                                            $redirect = '';
                                                            if(isset($_GET['redirect_to']) && $_GET['redirect_to']){
                                                                $redirect = $_GET['redirect_to'];
                                                            }
                                                            ?>
                                                            <a href="<?php echo $forgot.'?redirect_to='.$redirect; ?>">
                                                                <?php _e( 'Lost Password?', 'frontend-dashboard' ) ?>
                                                            </a>
                                                            <?php
                                                        } ?>
                                                        <input type="hidden" name="redirect_to" value="<?=$redirect?>">
                                                    </div>
                                                </div>
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
do_action( 'fed_after_login_only_form' );