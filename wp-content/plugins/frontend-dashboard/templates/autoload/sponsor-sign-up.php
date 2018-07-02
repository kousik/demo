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
    /**
     * Logged in User form
     */

    $details = fed_register_only();

//var_dump($details);


    do_action( 'fed_before_register_only_form' );
    ?>
    <?php if ( $login = fed_get_login_url() ) {
        $redirect = '';
        if(isset($_GET['redirect_to']) && $_GET['redirect_to']){
            $redirect = '?redirect_to='.$_GET['redirect_to'];
        }
        ?>
    <div class="bc_fed container fed_login_container">
        <?php echo fed_loader(); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="#">Sponsor Sign Up</a></li>
                    <li role="presentation"><a href="<?=site_url('sign-up').$redirect?>">Customer Sign Up</a></li>
                </ul>
                <div class="panel panel-primary">
                    <!--<div class="panel-heading">
						<h3 class="panel-title"><?php /*//echo $details['menu']['name']; */?> Customer Sign Up only</h3>
					</div>-->
                    <div class="panel-body">

                        <div class="fed_tab_content" data-id="<?php echo $details['menu']['id'] ?>">
                            <form method="post"
                                  class="fed_form_post"
                            >
                                <?php
                                $contents = $details['content'];
                                uasort( $contents, 'fed_sort_by_order' );
                                foreach ( $contents as $content ) {
                                    ?>
                                    <div class="form-group" <?=('User Role' == $content['name'])?'style="display:none;"':''?>>
                                        <label><?php echo $content['name'] ?></label>
                                        <?php echo $content['input'] ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <input type="hidden"
                                               name="submit"
                                               value="Register"/>
                                        <button class="btn btn-primary" type="submit"><?php echo $details['button'] ?></button>
                                    </div>

                                        <div class="col-md-12 padd_top_20 text-center">
                                            <a href="<?php echo $login.$redirect ?>"><?php _e( 'Already have an account?', 'frontend-dashboard' ) ?></a>
                                        </div>
                                    <?php } ?>
                                    <input type="hidden" name="redirect_to" value="<?=$redirect?>">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    do_action( 'fed_after_register_only_form' );
}
genesis();