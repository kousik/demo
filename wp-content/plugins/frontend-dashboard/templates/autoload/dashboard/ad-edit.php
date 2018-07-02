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
     * Dashboard Page
     *
     * @package frontend-dashboard
     */

    $dashboard_container = new FED_Routes( $_REQUEST );
    $menu                = $dashboard_container->setDashboardMenuQuery();

    do_action( 'fed_before_dashboard_container' );

    $id = epic_uri_slot(2);
    $id = encrypt_decrypt('decrypt',$id);
    $adv = pods( 'advertisement' );

    $params = array(
        'where'   => 't.id = '.$id,
        'limit'   => 1  // Return all rows
    );

    // Create and find in one shot
    $advt = pods( 'advertisement', $params );
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
                        <?php
                        $i  = 0;
                        while ( $advt->fetch() ):
                            $raw_data = $advt->data();
                            $raw_data = $raw_data[$i];?>
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <span class="fa fa-adn"></span>
                                    Update Advertisement :: <?=$advt->display('name');?>
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
                                            <input type="text" name="<?=$adv->pod_data['fields']['name']['name']?>" class="form-control" id="<?=$adv->pod_data['fields']['name']['name']?>" value="<?=$advt->display('name');?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['description']['label']?></label>
                                        <div class="col-sm-10">
                                            <textarea name="<?=$adv->pod_data['fields']['description']['name']?>" class="form-control" id="<?=$adv->pod_data['fields']['description']['name']?>" ><?=$raw_data->description;?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['url_link']['label']?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="<?=$adv->pod_data['fields']['url_link']['name']?>" class="form-control" id="<?=$adv->pod_data['fields']['url_link']['name']?>" value="<?=$advt->display('url_link');?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="state" class="col-sm-2 control-label"> State </label>
                                        <div class="col-sm-10">
                                            <select data-placeholder="Choose a State..." class="chosen-select js-state form-control" name="state" tabindex="2" required>
                                                <option value=""></option>
                                                <?php foreach (fdb_get_state() as $skey => $sobj):?>
                                                    <option value="<?=$sobj->name?>" data-id="<?=$sobj->id?>" <?=$advt->display('state')==$sobj->name?'selected':''?>><?=$sobj->name?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fname" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['sponsor_image']['label']?></label>
                                        <div class="col-sm-10">
                                            <?php
                                            $image = no_image_url();
                                            if($advt->display('sponsor_image')):
                                            $image = $advt->display('sponsor_image');
                                            endif;?>
                                            <img class="pull-left img-thumbnail" src="<?=$image;?>" alt="<?=$advt->display('name');?>" width="64" height="64" style="margin-right: 10px;"> <input type="file" id="rfpics" name="<?=$adv->pod_data['fields']['sponsor_image']['name']?>">
                                        </div>
                                    </div>
                                    <?php $valf = $advt->display( 'status', null, true );?>
                                    <div class="form-group">
                                        <label for="fname" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['status']['label']?></label>
                                        <div class="col-sm-10">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="<?=$adv->pod_data['fields']['status']['name']?>" value="1" <?=$valf=="Active"?"checked":""?>> &nbsp;Active
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="<?=$adv->pod_data['fields']['status']['name']?>" value="0" <?=$valf=="In-active"?"checked":""?>> &nbsp;In-Active
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

                                        $valf = $advt->display( 'is_premium', null, true );?>
                                        <div class="form-group">
                                            <label for="fname" class="col-sm-2 control-label">Type</label>
                                            <div class="col-sm-10">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$adv->pod_data['fields']['is_premium']['name']?>" value="1" <?=$valf=="Yes"?"checked":""?>> &nbsp;Sponsor Premier
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$adv->pod_data['fields']['is_premium']['name']?>" value="0" <?=$valf=="No"?"checked":""?>> &nbsp;Sponsor
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="fname" class="col-sm-2 control-label"><?=$adv->pod_data['fields']['packages']['label']?></label>
                                            <div class="col-sm-10">
                                                <?php
                                                if ( 0 < $packages->total() ):
                                                    $tot = $packages->total();
                                                    $i = 1;
                                                    while ( $packages->fetch() ):
                                                        ?>
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="<?=$adv->pod_data['fields']['packages']['name']?>" value="<?=$packages->display('id')?>" <?=$advt->display('packages')==$packages->display('name')?"checked":""?> > &nbsp;<?=$packages->display('name')?>
                                                            </label>
                                                        </div>
                                                        <?php
                                                        $i++;
                                                    endwhile;
                                                endif;?>
                                            </div>
                                        </div>
                                    <?php endif;?>



                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" name="etf-hub-form-submit" class="btn btn-warning">Update</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="fed_ajax_hook" value="update_user_ad" />
                                    <input type="hidden" name="id" value="<?=encrypt_decrypt('encrypt', $advt->display('id'))?>" />
                                    <input type="hidden" name="redirect" value="<?=curPageURL()?>">
                                    <?php wp_nonce_field('update-ad-nonce') ?>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                                            <div class="etf-community-ajax-feedback"></div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        <?php
                        endwhile; ?>
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


















