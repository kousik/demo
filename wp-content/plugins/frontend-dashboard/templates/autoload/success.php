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
    ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Request has been successfully submitted!</h2>
            <p>You will get all feature update notification on your requested e-mail id! Check your request status <a href="<?=site_url('sign-in')?>">here</a>.</p>
        </div>
    </div>

    <?php
}
genesis();