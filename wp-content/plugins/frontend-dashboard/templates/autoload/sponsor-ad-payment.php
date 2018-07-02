<?php
/**
 * Template Name: Roofing Request Template
 * Description: Used as a page template to show page contents, followed by a loop
 * through the "Genesis Office Hours" category
 */

if(!is_user_logged_in()):
    $url = curPageURL();
    wp_redirect(site_url('sign-up')."?redirect_to=".$url);exit;
endif;
remove_action( 'genesis_loop_else', 'genesis_do_noposts' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
// Add our custom loop
add_action( 'genesis_before_content', 'cd_goh_loop' );
function cd_goh_loop() {

    $id = epic_uri_slot(3);
    $id = encrypt_decrypt('decrypt', $id);
    $params = array(
        'where' => ' t.id = "'.$id.'" ',
        'limit' => 1  // Return all rows
    );
    $advs = pods('adv_package', $params);
    $pp = new WC_PP_PRO_Gateway();
    ?>

    <h2>Checkout</h2>
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Your Plan Information</div>
        <div class="panel-body">
            <!-- Table -->
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Info</th>
                    <th>Duration</th>
                    <th>Price</th>
                </tr>
                </thead>
                <?php
                if ( 0 < $advs->total() ):
                    $i = 0;
                    while ( $advs->fetch() ):
                        $raw_data = $advs->data();
                        $raw_data = $raw_data[$i];
                        ?>
                        <tr class="row-<?=encrypt_decrypt('encrypt', $advs->display('id'))?>">
                            <td><?=$advs->display('no_of_cards')?> card for <?=$advs->display('no_duration')?> <?=$advs->display('duration')?></td>
                            <td><?=$advs->display('no_duration')?> <?=$advs->display('duration');?></td>
                            <td><?=$advs->display('price');?></td>

                        </tr>
                    <?php
                    endwhile;
                else:
                    ?>
                    <tr>
                        <td colspan="5">
                            Oops! invalid request! <a href="<?=site_url('sponsor')?>" class="btn btn-sm btn-info">Back</a>
                        </td>
                    </tr>
                <?php
                endif;
                ?>
            </table>
        </div>
    </div>
    <?php
    if ( 0 < $advs->total() ):
        ?>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h2>Payment</h2>
                <?=$pp->payment_fields(epic_uri_slot(3))?>
            </div>
        </div>
    <?php endif;?>
    <?php
}
genesis();