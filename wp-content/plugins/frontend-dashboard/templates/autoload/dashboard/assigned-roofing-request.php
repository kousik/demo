<?php
$rc = pods('contractor');
$userinfo = wp_get_current_user();
$where = 'author.ID = "' . $userinfo->ID . '"';
$params = array(
    'where' => $where,
    'limit' => 1  // Return all rows
);


// Create and find in one shot
$contractor = pods('contractor', $params);
$edata = [];
while ($contractor->fetch()):
    $val = $contractor->field('assign_roofing_request', null, true);
    if ($val):
        foreach ($val as $vkey => $v):
            $edata[] = $v['id'];
        endforeach;
    endif;
endwhile;



$where = 'bidding_contractors.ID = "' . $userinfo->ID . '" OR assign_contractors.ID = "' . $userinfo->ID . '"';

if ($edata):
    $where .= " OR t.id IN (".implode(",", $edata).") ";
endif;

$params = array(
    'where' => $where,
    'limit' => -1  // Return all rows
);

// Create and find in one shot
$rf_requests = pods('roofhub_request', $params);

//echo "<pre>";print_r($rf_requests);
$style = "";
$cstyle = 'style="display:none;"';
if (isset($_GET['display']) && $_GET['display'] == 'view'):
    if (isset($_GET['rid']) && $_GET['rid']):
        $style = 'style="display:none;"';
        $cstyle = "";
    endif;
endif;

$bidstyle = 'style="display:none;"';
if (isset($_GET['display']) && $_GET['display'] == 'bid'):
    if (isset($_GET['reqid']) && $_GET['reqid']):
        $style = 'style="display:none;"';
        $bidstyle = "";
    endif;
endif;
?>
<div class="response" style="display: none;"></div>
<div class="table-responsive js-table-content" <?= $style ?>>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>State</th>
            <th>State</th>
            <th>Zip</th>
            <th>Status</th>
            <th>Created at</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (0 < $rf_requests->total()):
            $i = 1;
            while ($rf_requests->fetch()):
                //print_r($rf_requests);
                ?>

                <tr class="row-<?= encrypt_decrypt('encrypt', $rf_requests->display('id')) ?>">
                    <th scope="row"><?= $i ?></th>
                    <td><?= $rf_requests->display('first_name'); ?> <?= $rf_requests->display('last_name'); ?></td>
                    <td><?= $rf_requests->display('state'); ?></td>
                    <td><?= $rf_requests->display('city'); ?></td>
                    <td><?= $rf_requests->display('zip_code'); ?></td>

                    <td><?= $rf_requests->display('status'); ?></td>
                    <td><?= $rf_requests->display('created'); ?></td>
                    <td>
                        <?php
                        $view = site_url("/dashboard/") . "?menu_type=user&menu_slug=assigned_roofing_request&fed_nonce=" . wp_create_nonce('fed_nonce') . "&display=view&rid=" . encrypt_decrypt('encrypt', $rf_requests->display('id'));



                        $assign = site_url("/dashboard/") . "?menu_type=user&menu_slug=all_roofing_requests&display=assign&rid=" . encrypt_decrypt('encrypt', $rf_requests->display('id'));

                        $bidurl = site_url("/dashboard/") . "?menu_type=user&menu_slug=assigned_roofing_request&fed_nonce=" . wp_create_nonce('fed_nonce') . "&display=bid&reqid=" . encrypt_decrypt('encrypt', $rf_requests->display('id'));

                        ?>
                        <a class="btn btn-info" data-action="" href="<?= $view ?>" role="button" alt="View"
                           title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <?php
                        if(!in_array($rf_requests->display('id'), $edata)):
                            if ($rf_requests->display('assign_contractors')):
                                ?>
                                <a class="btn btn-success btn-xs" href="javascript://" role="button" alt="View"
                                   title="View">You Earned</a>
                            <?php
                            else:
                                $author = $rf_requests->field('author', true);
                                $params = array(
                                    'where' => ' author.ID = "'.$author['ID'].'" AND is_read = 0 AND roofing_request.id = ' . $rf_requests->display('id'),
                                    'orderby' => 't.id ASC',
                                    'limit' => -1  // Return all rows
                                );
                                $bcoms = pods('bidding_comment', $params);

                                ?>
                                <a class="btn btn-danger btn-xs" href="<?=$bidurl?>" role="button" alt="View" title="View">Bid Now</a> <?php if (0 < $bcoms->total()):?><span class="label label-primary"><?=$bcoms->total()?> New Message</span><?php endif;?>
                            <?php
                            endif;
                        else:
                            ?>
                            <a class="btn btn-success btn-xs js-accept-req" data-req="<?=encrypt_decrypt('encrypt', $rf_requests->display('id'))?>" href="javascript://" role="button" alt="Accept Request" title="View">Accept Now</a>
                            <?php
                        endif;

                        ?>


                        <!--<a class="btn btn-info js-view-data" data-action="roofing_view" data-req="<?= encrypt_decrypt('encrypt', $rf_requests->display('id')) ?>" href="javascript://" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <!--<a class="btn btn-warning js-view-data" data-action="roofing_edit" data-req="<?/*=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))*/
                        ?>" href="javascript://" role="button" alt="Assign" title="Assign"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a class="btn btn-danger js-request-delete" href="javascript://" role="button" alt="Delete" data-req="<?/*=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))*/
                        ?>" title="Delete"><i class="fa fa-close" aria-hidden="true"></i></a>-->
                    </td>
                </tr>
                <?php
                $i++;
            endwhile;
        else:
            ?>
            <tr>
                <th scope="row" colspan="9">Sorry! No data found!</th>
            </tr><?php

        endif;
        ?>
        </tbody>
    </table>
</div>
<div class="js-content-body" <?= $cstyle ?>>
    <?php
    if (isset($_GET['display']) && $_GET['display'] == 'view'):
    if (isset($_GET['rid']) && $_GET['rid']):
    $id = encrypt_decrypt('decrypt', $_GET['rid']);

    $rc = pods('roofhub_request');
    $data = [];

    $params = array(
        'where' => 't.id = ' . $id,
        'limit' => 1  // Return all rows
    );

    // Create and find in one shot
    $rrc = pods('roofhub_request', $params);
    $rrc_oth = pods('roofhub_request', $params);


    ?><?php
    $view = site_url("/dashboard/") . "?menu_type=user&menu_slug=assigned_roofing_request&fed_nonce=" . wp_create_nonce('fed_nonce');

    ?>
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-default" href="<?= $view ?>" role="button" alt="Close" title="Close"><i
                        class="fa fa-close" aria-hidden="true"></i></a>
        </div>
    </div>


    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#business" data-toggle="tab">Roofing Request Information</a>
        </li>
        <li><a href="#business-other" data-toggle="tab">Contact Information</a>
        </li>
    </ul>

    <div class="tab-content ">
        <div class="tab-pane well well-sm active" id="business">
            <h3>Information:</h3>

            <?php
            while ($rrc->fetch()):
                //$raw_data = $rrc->data();
                //$raw_data = $raw_data[0];print_r($raw_data);
                ?>
                <dl>
                    <dt><?= $rc->pod_data['fields']['building_type']['label'] ?></dt>
                    <dd><?= $rrc->display('building_type') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['building_level']['label'] ?></dt>
                    <dd><?= $rrc->display('building_level') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['roof_type']['label'] ?></dt>
                    <dd><?= $rrc->display('roof_type') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['property_accessible']['label'] ?></dt>
                    <dd><?= $rrc->display('property_accessible') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['roof_accessible']['label'] ?></dt>
                    <dd><?= $rrc->display('roof_accessible') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['roof_cond']['label'] ?></dt>
                    <dd><?= $rrc->display('roof_cond') ?></dd>
                </dl>

                <?php if ($rrc->display('roof_cond') == "Pitched Roof"): ?>
                <dl>
                    <dt><?= $rc->pod_data['fields']['pitch_type']['label'] ?></dt>
                    <dd><?= $rrc->display('pitch_type') ?></dd>
                </dl>
            <?php else: ?>
                <dl>
                    <dt><?= $rc->pod_data['fields']['flat_type']['label'] ?></dt>
                    <dd><?= $rrc->display('flat_type') ?></dd>
                </dl>
            <?php endif; ?>

                <dl>
                    <dt><?= $rc->pod_data['fields']['visible_water_damage']['label'] ?></dt>
                    <dd><?= $rrc->display('visible_water_damage') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['any_skylights']['label'] ?></dt>
                    <dd><?= $rrc->display('any_skylights') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['any_satellite']['label'] ?></dt>
                    <dd><?= $rrc->display('any_satellite') ?></dd>
                </dl>


                <dl>
                    <dt><?= $rc->pod_data['fields']['visible_damage']['label'] ?></dt>
                    <dd><?= $rrc->display('visible_damage') ?></dd>
                </dl>

                <dl>
                    <dt><?= $rc->pod_data['fields']['insurance_claim']['label'] ?></dt>
                    <dd><?= $rrc->display('insurance_claim') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['time_frame_needed']['label'] ?></dt>
                    <dd><?= $rrc->display('time_frame_needed') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['pictures_to_upload']['label'] ?></dt>
                    <dd><?= $rrc->display('pictures_to_upload') ?></dd>
                </dl>
                <?php if ($rrc->display('pictures_to_upload') == "Yes"):
                $gallery = $rrc->field('roof_pictures', true);
                ?>
                <dl>
                    <dt><?= $rc->pod_data['fields']['roof_pictures']['label'] ?></dt>
                    <dd>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                if (!isset($gallery[0])):
                                    $feat_image_url = wp_get_attachment_url($gallery['ID']);
                                    ?>
                                    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                        <a class="thumbnail" href="#" data-image-id="" data-toggle="modal"
                                           data-title="<?= $gallery['post_title'] ?>" data-caption=""
                                           data-image="<?= $feat_image_url ?>" data-target="#image-gallery">
                                            <img class="img-responsive" src="<?= $feat_image_url ?>"
                                                 alt="<?= $gallery['post_title'] ?>" style="height: 200px;">
                                        </a>
                                    </div>
                                <?php
                                else:
                                    foreach ($gallery as $pic):
                                        $feat_image_url = wp_get_attachment_url($pic['ID']); ?>

                                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                            <a class="thumbnail" href="#" data-image-id="" data-toggle="modal"
                                               data-title="<?= $pic['post_title'] ?>" data-caption=""
                                               data-image="<?= $feat_image_url ?>" data-target="#image-gallery">
                                                <img class="img-responsive" src="<?= $feat_image_url ?>"
                                                     alt="<?= $pic['post_title'] ?>" style="height: 200px;">
                                            </a>
                                        </div>

                                    <?php endforeach;
                                endif;
                                ?>
                            </div>
                    </dd>
                </dl>
            <?php endif; ?>
                <dl>
                    <dt><?= $rc->pod_data['fields']['general_contractor']['label'] ?></dt>
                    <dd><?= $rrc->display('general_contractor') ?></dd>
                </dl>
                <?php if ($rrc->display('general_contractor') == "Yes"): ?>
                <dl>
                    <dt><?= $rc->pod_data['fields']['roof_elevations_information']['label'] ?></dt>
                    <dd><?= $rrc->display('roof_elevations_information') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['roof_elevations_files']['label'] ?></dt>
                    <dd>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                $gallery = $rrc->field('roof_elevations_files', true);
                                $feat_image_url = wp_get_attachment_url($gallery['ID']); ?>
                                <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                    <a class="thumbnail" href="#" data-image-id="" data-toggle="modal"
                                       data-title="<?= $gallery['post_title'] ?>" data-caption=""
                                       data-image="<?= $feat_image_url ?>" data-target="#image-gallery">
                                        <img class="img-responsive" src="<?= $feat_image_url ?>"
                                             alt="<?= $gallery['post_title'] ?>" style="height: 200px;">
                                    </a>
                                </div>
                            </div>
                    </dd>
                </dl>
            <?php endif; ?>
            <?php
            endwhile;
            ?>

        </div>
        <div class="tab-pane well well-sm" id="business-other">
            <h3>Contact Information:</h3>
            <?php
            while ($rrc_oth->fetch()):?>
                <dl>
                    <dt>Name</dt>
                    <dd><?= $rrc_oth->display('first_name') ?> <?= $rrc_oth->display('last_name') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['address']['label'] ?></dt>
                    <dd><?= $rrc_oth->display('address') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['city']['label'] ?></dt>
                    <dd><?= $rrc_oth->display('city') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['state']['label'] ?></dt>
                    <dd><?= $rrc_oth->display('state') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['zip_code']['label'] ?></dt>
                    <dd><?= $rrc_oth->display('zip_code') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['email_address']['label'] ?></dt>
                    <dd><?= $rrc_oth->display('email_address') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['phone_number']['label'] ?></dt>
                    <dd><?= $rrc_oth->display('phone_number') ?></dd>
                </dl>
                <dl>
                    <dt><?= $rc->pod_data['fields']['best_time_contact']['label'] ?></dt>
                    <dd><?= $rrc_oth->display('best_time_contact') ?></dd>
                </dl>
                <!--<dl>
                                <dt><?/*=$rc->pod_data['fields']['bidding_contractors']['label']*/
                ?></dt>
                                <dd><?/*=$rrc_oth->display( 'bidding_contractors' )*/
                ?></dd>
                            </dl>
                            <dl>
                                <dt><?/*=$rc->pod_data['fields']['assign_contractors']['label']*/
                ?></dt>
                                <dd><?/*=$rrc_oth->display( 'assign_contractors' )*/
                ?></dd>
                            </dl>-->
            <?php
                //print_r($rrc_oth);
            endwhile;
            ?>
        </div>


        <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="image-gallery-title"></h4>
                    </div>
                    <div class="modal-body">
                        <img id="image-gallery-image" class="img-responsive" src="">
                    </div>
                    <!--<div class="modal-footer">

                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" id="show-previous-image">Previous</button>
                        </div>

                        <div class="col-md-8 text-justify" id="image-gallery-caption">
                            This text will be overwritten by jQuery
                        </div>

                        <div class="col-md-2">
                            <button type="button" id="show-next-image" class="btn btn-default">Next</button>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>


    </div>
<?php
endif;
endif ?>
</div>


<div class="row" <?= $bidstyle ?>>
    <?php
    $id = encrypt_decrypt('decrypt', $_GET['reqid']);
    $rc = pods('roofhub_request');
    $data = [];

    $params = array(
        'where' => 't.id = ' . $id,
        'limit' => 1  // Return all rows
    );
    $rrc = pods('roofhub_request', $params);

    $userinfo = wp_get_current_user();


    $params = array(
        'where' => 'roofing_request.id = ' . $id,
        'orderby' => 't.id ASC',
        'limit' => -1  // Return all rows
    );
    $bcoms = pods('bidding_comment', $params);



    //Reset count
    $author = $rrc->field('author', true);
    $params = array(
        'where' => ' author.ID = "'.$author['ID'].'" AND is_read = 0 AND roofing_request.id = ' . $rrc->display('id'),
        'orderby' => 't.id ASC',
        'limit' => -1  // Return all rows
    );
    $bidcoms = pods('bidding_comment', $params);
    if ( 0 < $bidcoms->total() ):
        while ( $bidcoms->fetch() ):
            $bidpod = pods('bidding_comment', $bidcoms->display('id'));
            $bidpod->save( 'is_read', 1, $bidcoms->display('id') );
        endwhile;
    endif;
    ?>

    <div class="">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>State</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Status</th>
                    <th>Created at</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $rrc->display('first_name'); ?> <?= $rrc->display('last_name'); ?></td>
                    <td><?= $rrc->display('state'); ?></td>
                    <td><?= $rrc->display('city'); ?></td>
                    <td><?= $rrc->display('zip_code'); ?></td>
                    <td><?= $rrc->display('status'); ?></td>
                    <td><?= $rrc->display('created'); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8 col-md-offset-2">
            <h2 class="page-header">Comments</h2>
            <section class="comment-list">
                <?php
                if ( 0 < $bcoms->total() ):
                    $i = 1;
                    while ( $bcoms->fetch() ):
                        $contractor = $bcoms->field('author', true, true  );

                        if($contractor['ID'] == $userinfo->ID):
                            $params = array(
                                'where' => 'author.ID = ' . $contractor['ID'],
                                'limit' => 1  // Return all rows
                            );
                            $con = pods('contractor', $params);
                ?>
                            <!-- First Comment -->
                            <article class="row">
                                <div class="col-md-2 col-sm-2 hidden-xs">
                                    <figure class="thumbnail">
                                        <img class="img-responsive img-circle" src="<?=no_image_url()?>" />
                                        <figcaption class="text-center">Me (<?= $con->display('name'); ?>) </figcaption>
                                    </figure>
                                </div>
                                <div class="col-md-10 col-sm-10">
                                    <div class="panel panel-default arrow left">
                                        <div class="panel-body">
                                            <header class="text-left">
                                                <div class="comment-user"><i class="fa fa-user"></i> <?= $con->display('name'); ?></div>
                                                <time class="comment-date" datetime="<?=$bcoms->display('created')?>"><i class="fa fa-clock-o"></i> <?=$bcoms->display('created')?></time>
                                            </header>
                                            <div class="comment-post">
                                                <p>
                                                    <?=nl2br($bcoms->display('comments'))?>
                                                </p>
                                            </div>
                                            <?php if($bcoms->display('price') != "$0.00"):?><p class="text-right"> Offered Price <a class="btn btn-danger btn-sm"><?=$bcoms->display('price')?></a> </p><?php endif;?>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php
                        else:
                            $params = array(
                                'where' => 'author.ID = ' . $contractor['ID'],
                                'limit' => 1  // Return all rows
                            );
                            $con = pods('contractor', $params);
                        ?>
                            <!-- Third Comment -->
                            <article class="row">
                                <div class="col-md-10 col-sm-10">
                                    <div class="panel panel-default arrow right">
                                        <div class="panel-body">
                                            <header class="text-right">
                                                <div class="comment-user"><i class="fa fa-user"></i> <?= $rrc->display('first_name'); ?> <?= $rrc->display('last_name'); ?></div>
                                                <time class="comment-date" datetime="<?=$bcoms->display('created')?>"><i class="fa fa-clock-o"></i> <?=$bcoms->display('created')?></time>
                                            </header>
                                            <div class="comment-post">
                                                <p>
                                                    <?=nl2br($bcoms->display('comments'))?>
                                                </p>
                                            </div>
                                            <?php if($bcoms->display('price') != "$0.00"):?><p class="text-right"> Counter Price <a class="btn btn-danger btn-sm"><?=$bcoms->display('price')?></a> <?php if($bcoms->display('price_accept') == 'Yes'):?> <span class="label label-success">Accepted</span><?php else:?><a class="btn btn-sm btn-success js-accept-offer" data-comment="<?=$bcoms->display('id')?>" data-con="<?=$contractor['ID']?>"  data-rq="<?=$rrc->display('id')?>">Accept</a><?php endif;?></p><?php endif;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 hidden-xs">
                                    <figure class="thumbnail">
                                        <img class="img-responsive img-circle" src="<?=no_image_url()?>" />
                                        <figcaption class="text-center"><?= $rrc->display('first_name'); ?> <?= $rrc->display('last_name'); ?></figcaption>
                                    </figure>
                                </div>
                            </article>

                <?php
                        endif;
                    endwhile;
                else:
                    ?>
                    <article class="row">
                        <h3>Sorry! No comments available!</h3>
                    </article>
                <?php
                endif;
                ?>
            </section>

            <?php
            $params = array(
                'where' => ' price_accept = 1 AND roofing_request.id = ' . $rrc->display('id'),
                'orderby' => 't.id ASC',
                'limit' => 1  // Return all rows
            );
            $price = pods('bidding_comment', $params);
            if ( 0 > $price->total() ):
            ?>
            <hr>
            <h2>Reply ...</h2>
            <form  id="etf-hub-form"  action="<?php echo site_url( 'wp-load.php' );?>" class="form-horizontal" method="post">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Comments</label>
                    <div class="col-sm-10">
                        <textarea name="comments" class="form-control" id="comments" placeholder="Comments"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Offer Price</label>
                    <div class="col-sm-10">
                        <input type="text" name="price" class="form-control" id="price" placeholder="price">
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-warning" name="etf-hub-form-submit">Submit</button>
                    </div>
                </div>
                <input type="hidden" name="fed_ajax_hook" value="submit_bid_comment" />
                <input type="hidden" name="roofing_request" value="<?=encrypt_decrypt('encrypt',$id);?>">
                <input type="hidden" name="author" value="<?=$userinfo->ID?>">
                <?php wp_nonce_field('bid-modify-nonce') ?>

                <div class="form-group">
                    <div class="col-sm-8">
                        <div class="et-ajax-loader-global etf-community-module-loader"><span>Processing...</span></div>
                        <div class="etf-community-ajax-feedback"></div>
                    </div>
                </div>
            </form>
            <?php
            else:
                $ac = $rrc->field('assign_contractors', true);
                $params = array(
                    'where' => 'author.ID = ' . $ac['ID'],
                    'limit' => 1  // Return all rows
                );
                $con = pods('contractor', $params);
                ?>
                <h1>Bid accepted by <strong><?=$con->display('name')?></h1>

            <?php

            endif;
            ?>
        </div>
    </div>

</div>