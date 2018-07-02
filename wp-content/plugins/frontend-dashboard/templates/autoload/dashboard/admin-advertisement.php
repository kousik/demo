<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 29/5/18
 * Time: 11:22 PM
 */

$adv_info = get_sponsor_adinfo();
$dashboard_container = new FED_Routes( $_REQUEST );
$menu                = $dashboard_container->setDashboardMenuQuery();


?>
<style>
    .bc_fed.fed_dashboard_container label {display: block !important;}
</style>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-8">
                <h2>Wel Come to RoofHub Advertisements</h2>

            </div>
            <div class="col-xs-4">
                <div class="row">
                    <div class="col-xs-12">
                        <?php if(!is_super_admin()):
                            $type = $adv_info['type'];
                            if($type && $type < 2):
                                if($type && $type == 1):
                                    ?>
                                    <button type="button" class="btn btn-warning pull-right" data-toggle="modal" data-target="#upModal">Upgrade to Premier</button>
                                    <?php
                                    /*elseif($type && $type == 2):
                                        */?><!--
                                        <a href="<?/*=site_url('sponsor')*/?>"><button type="button" class="btn btn-warning pull-right">Add another Advertisements</button></a>
                                    --><?php
                                else:
                                    ?>
                                    <button type="button" class="btn btn-warning pull-right" data-toggle="modal" data-target="#newModal">Want to Advertise</button>
                                <?php
                                endif;
                            else:
                                ?>
                                <button type="button" class="btn btn-warning pull-right" data-toggle="modal" data-target="#newModal">Want to Advertise</button>
                            <?php
                            endif;

                        endif;?>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-xs-12">
                        <?php if(!is_super_admin()):
                            $type = $adv_info['type'];

                            if($type && $type == 1):
                                ?>
                                <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#moresponsorModal">Add More</button>
                            <?php
                            endif;
                            if($type && $type == 2):
                                ?>
                                <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#morepremierModal">Add More</button>
                            <?php
                            endif;

                        endif;?>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">

    <!-- Default panel contents -->
    <div class="panel-heading"><?php if(is_super_admin()):?>All<?php else:?>My<?php endif;?> Advertisements </div>
    <div class="panel-body">
        <?php if(is_super_admin() || $adv_info['can_add']):?>
            <a href="<?=site_url('sponsor/add/new')?>"><button type="button" class="btn btn-warning pull-right">Add</button></a>
        <?php endif;?>

        <!-- Table -->
        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#Ad ID</th>
                <th>Info</th>
                <?php if(is_super_admin()):?>
                <th>User</th>
                <?php endif;?>
                <th>State</th>
                <th>Status</th>
                <th>Type</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <?php $advs = $adv_info['advs'];
            if ( 0 < $advs->total() ):
            $i = 0;
            while ( $advs->fetch() ):
                $raw_data = $advs->data();
                $raw_data = $raw_data[$i];
            ?>
            <tr class="row-<?=encrypt_decrypt('encrypt', $advs->display('id'))?>">
                <td>AD-RH-<?=$advs->display('id');?></td>
                <td style="width: 40%">
                    <div class="media">
                        <div class="media-body">
                            <?php
                            $image = no_image_url();
                            if($advs->display('sponsor_image')):
                                $image = $advs->display('sponsor_image');
                            endif;
                            ?>
                            <a href="<?=$advs->display('url_link')?$advs->display('url_link'):"javascript://";?>" target="_blank"><img class="media-object pull-left img-thumbnail" src="<?=$image;?>" alt="<?=$advs->display('name');?>" width="64" height="64" style="margin-right: 10px;"></a>
                            <h4 class="media-heading"><?=$advs->display('name');?></h4>
                            <?=$advs->display('description');?>

                        </div>
                    </div>
                </td>
                <?php if(is_super_admin()):?>
                <td><?=$advs->display('author');?></td>
                <?php endif;?>
                <td><?=$advs->display('state');?></td>
                <td><?=$advs->display('status');?></td>
                <td><?=$advs->display('is_premium')=="Yes"?"Premier":"Sponsor";?></td>
                <td><?=$advs->display('created');?></td>
                <td>
                    <?php /*if(!is_super_admin() && !$raw_data->is_premium):*/?><!--
                    <a class="btn btn-success" href="<?/*=site_url('sponsor');*/?>?ad-id=<?/*=encrypt_decrypt('encrypt', $advs->display('id'))*/?>" role="button">Make premium</a>
                    --><?php /*endif;*/?>
                    <a class="btn btn-info btn-xs" href="<?=site_url('dashboard/edit-ad/').encrypt_decrypt('encrypt', $advs->display('id'))?>" role="button"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger btn-xs js-ad-delete" data-req="<?=encrypt_decrypt('encrypt', $advs->display('id'))?>" href="javascript://" role="button"><i class="fa fa-times-circle"></i></a>
                </td>
            </tr>
            <?php
            $i++;
            endwhile;
            else:
                ?>
            <tr>
                <td colspan="8">
                    Sorry! You have no any published advertisement!
                </td>
            </tr>
            <?php
            endif;
            ?>

        </table>
        </div>
    </div>
</div>

<?php
//unset($_SESSION['pop_show']);
if($adv_info['popup'] && !isset($_SESSION['pop_show'])):
    if(!is_super_admin()):
        ?>
        <script type="application/javascript">
            jQuery(document).ready(function () {
                jQuery('#newModal').modal('show');
            });
        </script><?php
            $_SESSION['pop_show'] = true;
    endif;
endif;
?>




<!-- Modal -->
<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="etf-hub-form-rf" name="etf-community-form" data-cl="roofing" class="modalform" enctype="multipart/form-data" method="post">
                    <div class="etf-community-ajax-feedback"></div>
                    <fieldset>
                        <h4 class="text-center">Sponsors receive advertising cards in their respective state & Premier Sponsors receive advertising cards in all states</h4>
                        <p style="padding-top:10px;">
                        <button type="button" class="btn btn-default btn-lg btn-block js-s-nxt" data-val="sponsor">Sponsor</button>
                        <button type="button" class="btn btn-primary btn-lg btn-block js-s-nxt" data-val="premium">Premier Sponsors</button>
                        </p>
                        <p style="padding-top:10px;display: none;" class="text-center js-ms-msg"></p>
                        <br>
                        <input type="button" name="next" class="newnext action-button pull-right js-show-nxt-button" style="display: none;" value="Next" />
                    </fieldset>


                    <fieldset>
                        <div class="form-group">
                            <h4 class="text-center">Select a card</h4>
                            <div class="frb-group js-all-packeges"></div>
                        </div>
                        <input type="button" name="previous" class="newprevious action-button" value="Previous" />
                        <input type="button" name="next" class="newnext action-button pull-right js-package-submit" value="Submit" />
                    </fieldset>
                    <input type="hidden" name="ad_type" value="" class="js-ad-type">
                    <input type="hidden" name="p_type" value="" class="js-p-type">

                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="upModal" tabindex="-1" role="dialog" aria-labelledby="upModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="etf-hub-form-rf" name="etf-community-form" data-cl="roofing" class="modalform" enctype="multipart/form-data" method="post">
                    <div class="etf-community-ajax-feedback"></div>
                    <fieldset>
                        <h4 class="text-center">Premier Sponsors receive advertising cards in all states</h4>
                        <div class="frb-group">
                            <div class="frb frb-warning">
                                <input type="radio" id="radio-button-0" name="premium" value="premium" checked>
                                <label for="checkbox-0">
                                    <span class="frb-title">Premier Sponsor</span>
                                    <!--<span class="frb-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In semper quam nunc.</span>-->
                                </label>
                            </div>
                        </div>
                        <input type="button" name="next" class="upwnext action-button pull-right js-show-nxt-button" value="Next" />
                    </fieldset>


                    <fieldset>
                        <div class="form-group">
                            <h4 class="text-center">Select a card</h4>
                            <div class="frb-group js-all-packeges"></div>
                        </div>
                        <input type="button" name="previous" class="newprevious action-button" value="Previous" />
                        <input type="button" name="next" class="upwnext action-button pull-right js-package-submit" value="Submit" />
                    </fieldset>
                    <input type="hidden" name="ad_type" value="premium" class="js-ad-type">
                    <input type="hidden" name="p_type" value="" class="js-p-type">

                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="moresponsorModal" tabindex="-1" role="dialog" aria-labelledby="moresponsorModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="etf-hub-form-rf" name="etf-community-form" data-cl="roofing" class="modalform" enctype="multipart/form-data" method="post">
                    <div class="etf-community-ajax-feedback"></div>
                    <fieldset>
                        <h4 class="text-center">Sponsors receive advertising cards in their respective state</h4>
                        <div class="frb-group">
                            <div class="frb frb-warning">
                                <input type="radio" id="radio-button-0" name="sponsor" value="sponsor" checked>
                                <label for="checkbox-0">
                                    <span class="frb-title">Sponsor</span>
                                    <!--<span class="frb-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In semper quam nunc.</span>-->
                                </label>
                            </div>
                        </div>
                        <input type="button" name="next" class="msnext action-button pull-right js-show-nxt-button" value="Next" />
                    </fieldset>


                    <fieldset>
                        <div class="form-group">
                            <h4 class="text-center">Select a card</h4>
                            <div class="frb-group js-all-packeges"></div>
                        </div>
                        <input type="button" name="previous" class="newprevious action-button" value="Previous" />
                        <input type="button" name="next" class="msnext action-button pull-right js-package-submit" value="Submit" />
                    </fieldset>
                    <input type="hidden" name="ad_type" value="sponsor" class="js-ad-type">
                    <input type="hidden" name="p_type" value="" class="js-p-type">

                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="morepremierModal" tabindex="-1" role="dialog" aria-labelledby="morepremierModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="etf-hub-form-rf" name="etf-community-form" data-cl="roofing" class="modalform" enctype="multipart/form-data" method="post">
                    <div class="etf-community-ajax-feedback"></div>
                    <fieldset>
                        <h4 class="text-center">Premier Sponsors receive advertising cards in all states</h4>
                        <div class="frb-group">
                            <div class="frb frb-warning">
                                <input type="radio" id="radio-button-0" name="premium" value="premium" checked>
                                <label for="checkbox-0">
                                    <span class="frb-title">Premier Sponsor</span>
                                    <!--<span class="frb-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In semper quam nunc.</span>-->
                                </label>
                            </div>
                        </div>
                        <input type="button" name="next" class="mpnext action-button pull-right js-show-nxt-button" value="Next" />
                    </fieldset>


                    <fieldset>
                        <div class="form-group">
                            <h4 class="text-center">Select a card</h4>
                            <div class="frb-group js-all-packeges"></div>
                        </div>
                        <input type="button" name="previous" class="newprevious action-button" value="Previous" />
                        <input type="button" name="next" class="mpnext action-button pull-right js-package-submit" value="Submit" />
                    </fieldset>
                    <input type="hidden" name="ad_type" value="premium" class="js-ad-type">
                    <input type="hidden" name="p_type" value="" class="js-p-type">

                </form>
            </div>
        </div>
    </div>
</div>