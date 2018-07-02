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
        <div class="col-md-6 col-md-offset-3">

            <div class="panel panel-success">
                <div class="panel-heading"><h2>Thank you for your payment</h2></div>
                <div class="panel-body">
                    <p>Go your account & Check your new premium ad status <a href="<?=site_url('dashboard')?>">here</a>.</p>
                </div>
            </div>


        </div>
    </div>

    <?php
}
genesis();