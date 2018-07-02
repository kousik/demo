<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 2/5/18
 * Time: 10:17 PM
 */

if(!is_super_admin() && !current_user_can('sponsor')):
    wp_redirect(site_url('/404/'));
    exit;
endif;
$adv_info = get_sponsor_adinfo();
if(!is_super_admin() && !$adv_info['can_add']):
    wp_redirect(site_url('/dashboard/'));exit;
endif;


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
     * Dashboard Page
     *
     * @package frontend-dashboard
     */

    $dashboard_container = new FED_Routes( $_REQUEST );
    $menu                = $dashboard_container->setDashboardMenuQuery();

    do_action( 'fed_before_dashboard_container' );





    $packeges = '';
    if(current_user_can('sponsor')):
        $all_packeges = get_sponsor_existing_cards_package();
        //print_r($all_packeges);
    endif;
    $adv = pods( 'advertisement' );
    ?>
    <div class="bc_fed fed_dashboard_container">
        <?php echo fed_loader() ?>
        <?php if ( ! $menu instanceof WP_Error ) { ?>
            <div class="row fed_dashboard_wrapper">
                <div class="col-md-3 fed_dashboard_menus default_template">
                    <div class="custom-collapse fed_menu_items">
                        <button class="bg_secondary collapse-toggle visible-xs collapsed" type="button" data-toggle="collapse" data-parent="custom-collapse" data-target="#fed_default_template">
                            <span class=""><i class="fa fa-bars"></i></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <ul class="list-group fed_menu_ul collapse" id="fed_default_template">
                            <?php
                            fed_display_dashboard_menu( $menu );

                            fed_get_collapse_menu() ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9 fed_dashboard_items">
                    <div class="panel panel-primary fed_dashboard_item">

                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <span class="fa fa-adn"></span>
                                    Add Advertisement
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="clearfix">
                                <a class="btn btn-info pull-right" href="<?=site_url('dashboard/')?>?menu_type=user&menu_slug=my_advertisement&fed_nonce=<?=wp_create_nonce( 'fed_nonce' )?>" role="button">Back</a>
                                </div>
                                <br>

                                <form id="etf-hub-form" name="etf-community-form" class="ad-update form-horizontal" action="<?php echo site_url( 'wp-load.php' );?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['name']['label']?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="<?=$adv->pod_data['fields']['name']['name']?>" class="form-control" id="<?=$adv->pod_data['fields']['name']['name']?>" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['description']['label']?></label>
                                        <div class="col-sm-10">
                                            <textarea name="<?=$adv->pod_data['fields']['description']['name']?>" class="form-control" id="<?=$adv->pod_data['fields']['description']['name']?>" ></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['url_link']['label']?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="<?=$adv->pod_data['fields']['url_link']['name']?>" class="form-control" id="<?=$adv->pod_data['fields']['url_link']['name']?>" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="state" class="col-sm-2 control-label"> State </label>
                                        <div class="col-sm-10">
                                            <select data-placeholder="Choose a State..." class="chosen-select js-state form-control" name="state" tabindex="2" required>
                                                <option value=""></option>
                                                <?php foreach (fdb_get_state() as $skey => $sobj):?>
                                                    <option value="<?=$sobj->name?>" data-id="<?=$sobj->id?>" ><?=$sobj->name?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fname" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['sponsor_image']['label']?></label>
                                        <div class="col-sm-10">
                                            <?php
                                            $image = no_image_url();
                                            ?>
                                            <img class="pull-left img-thumbnail" src="<?=$image;?>" alt="" width="64" height="64" style="margin-right: 10px;"> <input type="file" id="rfpics" name="<?=$adv->pod_data['fields']['sponsor_image']['name']?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fname" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['status']['label']?></label>
                                        <div class="col-sm-10">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="<?=$adv->pod_data['fields']['status']['name']?>" value="1" checked> &nbsp;Active
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="<?=$adv->pod_data['fields']['status']['name']?>" value="0"> &nbsp;In-Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(is_super_admin()):
                                            $params = array(
                                                'orderby' => 't.duration ASC',
                                                'limit'   => -1  // Return all rows
                                            );

                                            $packages = pods( 'adv_package', $params );

                                        ?>


                                        <div class="form-group">
                                            <label for="fname" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['packages']['label']?></label>
                                            <div class="col-sm-10">
                                                <?php
                                                if ( 0 < $packages->total() ):
                                                    $i = 0;
                                                    while ( $packages->fetch() ):
                                                        $raw_data = $packages->data();
                                                        $raw_data = $raw_data[$i];
                                                    ?>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="<?=$adv->pod_data['fields']['packages']['name']?>" value="<?=$packages->display('id')?>" <?=$i==0?'checked':''?> data-type="<?=$raw_data->type?>"> &nbsp;<?=$packages->display('name')?>
                                                        </label>
                                                    </div>
                                                    <?php
                                                    $i++;
                                                    endwhile;
                                                endif;?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="state" class="col-sm-2 control-label">AD location </label>
                                            <div class="col-sm-10">
                                                <select class="form-control ad-location" name="ad_location">
                                                    <option value=""> Select </option>
                                                </select><br>
                                                <small>This AD will also display selected location as per your choice from above dropdown. </small>
                                            </div>
                                        </div>
                                        <script type="application/javascript">
                                            jQuery(document).ready(function () {
                                                var type = jQuery("input[name='packages']:checked").attr('data-type');
                                                jQuery.ajax({
                                                    method: "POST",
                                                    url: etajaxurl,
                                                    data: {
                                                        fed_ajax_hook: "get_ad_location",
                                                        type: type
                                                    },
                                                    dataType: 'json',
                                                    success: function (resp) {
                                                        jQuery('select.ad-location').empty();
                                                        jQuery('select.ad-location').html(resp.data);
                                                    }
                                                });
                                            });
                                        </script>

                                        <div class="form-group">
                                            <label for="fname" class="col-sm-2 control-label">Type</label>
                                            <div class="col-sm-10">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$adv->pod_data['fields']['is_premium']['name']?>" value="1" checked> &nbsp;Sponsor Premier
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$adv->pod_data['fields']['is_premium']['name']?>" value="0"> &nbsp;Sponsor
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                    <?php endif;?>

                                    <?php if(current_user_can('sponsor')):?>
                                        <div class="form-group">
                                            <label for="fname" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['packages']['label']?></label>
                                            <div class="col-sm-10">
                                                <?php
                                                if ( $all_packeges ):
                                                    $i = 1;
                                                    foreach ( $all_packeges as $akey => $pack ):
                                                        ?>
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="pack_info" value="<?=$pack->id?>__<?=$pack->ad_id?>" <?=$i==1?'checked':''?> data-type="<?=$pack->adinfo['type']?>" class="sponsor_packs"> &nbsp;<?=($pack->adinfo['type'] == 1)?"Sponsor":"Premier Sponsor"?> $<?=$pack->adinfo['price']?>/<?=($pack->adinfo['duration'] == 3)?"Year":"Month"?> (Remaining Ad - <?=$pack->no_card - $pack->used_card?>)
                                                            </label>
                                                        </div>
                                                        <?php
                                                        $i++;
                                                    endforeach;
                                                endif;?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="state" class="col-sm-2 control-label">AD location </label>
                                            <div class="col-sm-10">
                                                <select class="form-control ad-location" name="ad_location">
                                                    <option value=""> Select </option>
                                                </select><br>
                                                <small>This AD will also display selected location as per your choice from above dropdown.</small>
                                            </div>
                                        </div>
                                        <script type="application/javascript">
                                            jQuery(document).ready(function () {
                                                var packege = jQuery("input[name='pack_info']:checked").val();
                                                var type = jQuery("input[name='pack_info']:checked").attr('data-type');
                                                jQuery.ajax({
                                                    method: "POST",
                                                    url: etajaxurl,
                                                    data: {
                                                        fed_ajax_hook: "get_ad_location",
                                                        type: type
                                                    },
                                                    dataType: 'json',
                                                    success: function (resp) {
                                                        jQuery('select.ad-location').empty();
                                                        jQuery('select.ad-location').html(resp.data);
                                                    }
                                                });
                                            });
                                        </script>
                                    <?php endif;?>



                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" name="etf-hub-form-submit" class="btn btn-warning">Add</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="fed_ajax_hook" value="add_user_ad" />
                                    <input type="hidden" name="redirect" value="<?=site_url('dashboard')?>?menu_type=user&menu_slug=my_advertisement&fed_nonce=<?=wp_create_nonce( 'fed_nonce' )?>">
                                    <?php wp_nonce_field('add-ad-nonce') ?>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                                            <div class="etf-community-ajax-feedback"></div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                    </div>
                </div>
            </div>
            <?php
        }

        if ( $menu instanceof WP_Error ) {
            ?>
            <div class="row fed_dashboard_wrapper fed_error">
                <?php fed_get_403_error_page() ?>
            </div>
            <?php
        } ?>
    </div>
    <?php
    do_action( 'fed_after_dashboard_container' );

}
genesis();


















