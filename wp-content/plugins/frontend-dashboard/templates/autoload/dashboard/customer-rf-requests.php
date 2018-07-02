<?php
$userinfo = wp_get_current_user();
$where = 'author.ID = "' . $userinfo->ID . '"';
if(is_super_admin()):
    $where = ' 1=1 ';
endif;
$params = array(
    'where' => $where,
    'limit' => -1  // Return all rows
);

// Create and find in one shot
$rf_requests = pods( 'roofhub_request', $params );
$style = "";
$cstyle = 'style="display:none;"';
if(isset($_GET['display']) && $_GET['display'] == 'viewcon'):
    if(isset($_GET['cid']) && $_GET['cid']):
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

$bidliststyle = 'style="display:none;"';
if (isset($_GET['display']) && $_GET['display'] == 'bidlist'):
    if (isset($_GET['rlid']) && $_GET['rlid']):
        $style = 'style="display:none;"';
        $bidliststyle = "";
    endif;
endif;
?>
    <div class="response" style="display: none;"></div>
    <div class="table-responsive js-table-content" <?=$style?>>
        <table class="table table-hover"> <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>State</th>
                <th>State</th>
                <th>Zip</th>
                <th>Assigned Contractors</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ( 0 < $rf_requests->total() ):
                $i = 1;
                while ( $rf_requests->fetch() ):
                    //print_r($rf_requests);
                    ?>

                    <tr class="row-<?=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))?>">
                        <th scope="row"><?=$i?></th>
                        <td><?=$rf_requests->display('first_name');?> <?=$rf_requests->display('last_name');?></td>
                        <td><?=$rf_requests->display('state');?></td>
                        <td><?=$rf_requests->display('city');?></td>
                        <td><?=$rf_requests->display('zip_code');?></td>
                        <td><?=$rf_requests->display('assign_contractors');?></td>

                        <td><?=$rf_requests->display('status');?></td>
                        <td><?=$rf_requests->display('created');?></td>
                        <td>
                            <?php
                            $view = site_url("/dashboard/")."?menu_type=user&menu_slug=all_roofing_requests&display=view&rid=".encrypt_decrypt('encrypt',$rf_requests->display( 'id' ));
                            $assign = site_url("/dashboard/")."?menu_type=user&menu_slug=all_roofing_requests&display=assign&rid=".encrypt_decrypt('encrypt',$rf_requests->display( 'id' ));


                            $bidlist = site_url("/dashboard/")."?menu_type=user&menu_slug=your_roofing_requests&display=bidlist&rlid=".encrypt_decrypt('encrypt',$rf_requests->display( 'id' ));
                            ?>
                            <a class="btn btn-info js-view-data" data-action="roofing_view" data-req="<?=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))?>" href="javascript://" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <!--<a class="btn btn-warning js-view-data" data-action="roofing_edit" data-req="<?/*=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))*/?>" href="javascript://" role="button" alt="Assign" title="Assign"><i class="fa fa-edit" aria-hidden="true"></i></a>-->
                            <?php
                            if(!$rf_requests->display( 'assign_contractors' ) && !$rf_requests->display( 'bidding_contractors' )):
                            ?>
                            <a class="btn btn-danger js-request-delete" href="javascript://" role="button" alt="Delete" data-req="<?=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))?>" title="Delete"><i class="fa fa-close" aria-hidden="true"></i></a>
                            <?php endif;?>

                            <?php
                            if(!$rf_requests->display( 'assign_contractors' ) && $rf_requests->display( 'bidding_contractors' )):

                                //Reset count
                                $params = array(
                                    'where' => ' author.ID != "'.$userinfo->ID.'" AND is_read = 0',
                                    'orderby' => 't.id ASC',
                                    'limit' => -1  // Return all rows
                                );
                                $bidcoms = pods('bidding_comment', $params);
                                ?>
                                <a class="btn btn-success" href="<?=$bidlist?>" role="button" alt="Comments"title="Comments">Comments</a> <?php if (0 < $bidcoms->total()):?><span class="label label-primary"><?=$bidcoms->total()?> New Message</span><?php endif;?>
                            <?php endif;?>
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
<div class="js-content-body" <?=$cstyle?>>
    <?php
    if(isset($_GET['display']) && $_GET['display'] == 'viewcon'):
        if(isset($_GET['cid']) && $_GET['cid']):
            $uid = encrypt_decrypt('decrypt',$_GET['cid']);
            $rc = pods( 'contractor' );
            $data = [];

            $params = array(
                'where'   => 'author.ID = '.$uid,
                'limit'   => 1  // Return all rows
            );

            // Create and find in one shot
            $contractor = pods( 'contractor', $params );
            $contractor_oth = pods( 'contractor', $params );
            $ctype = $_GET['ctype'];
            $link = site_url("/dashboard/")."?menu_type=user&menu_slug=your_roofing_requests&fed_nonce=" . wp_create_nonce( 'fed_nonce' );
            ?>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a class="btn btn-default" href="<?=$link?>" role="button" alt="Close" data-req="<?=encrypt_decrypt('encrypt',$id)?>" title="Close"><i class="fa fa-close" aria-hidden="true"></i></a>
                </div>

            </div>



            <ul class="nav nav-tabs">
                <li class="active">
                    <a  href="#business" data-toggle="tab">Business Contact Information</a>
                </li>
                <li><a href="#business-other" data-toggle="tab">Business Other Information</a>
                </li>
            </ul>

            <div class="tab-content ">
                <h2>Contractor Information:</h2>
                <div class="tab-pane well well-sm active" id="business">
                    <h3>Information:</h3>
                    <?php
                    while ( $contractor->fetch() ):?>
                    <?php
                    if($contractor->display( 'company_logo' )):
                        ?>
                        <div class="form-group">

                            <?php
                            $gallery = $contractor->field('company_logo', true);
                            $feat_image_url = wp_get_attachment_url( $gallery['ID'] );?>
                            <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="<?=$gallery['post_title']?>" data-caption="" data-image="<?=$feat_image_url?>" data-target="#image-gallery">
                                <img class="img-responsive" src="<?=$feat_image_url?>" alt="<?=$gallery['post_title']?>"  style="height: 200px;">
                            </a>
                        </div>
                    <?php endif;?>

                    <?php if($ctype == 'ac'):?>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['name']['label']?></dt>
                            <dd><?=$contractor->display( 'name' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['registry_or_license_number']['label']?></dt>
                            <dd><?=$contractor->display( 'registry_or_license_number' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['business_address']['label']?></dt>
                            <dd><?=$contractor->display( 'business_address' )?></dd>
                        </dl>
                    <?php endif;?>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['city']['label']?></dt>
                            <dd><?=$contractor->display( 'city' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['state']['label']?></dt>
                            <dd><?=$contractor->display( 'state' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['zip_code']['label']?></dt>
                            <dd><?=$contractor->display( 'zip_code' )?></dd>
                        </dl>
                    <?php if($ctype == 'ac'):?>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['title']['label']?></dt>
                            <dd><?=$contractor->display( 'title' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['contact_name']['label']?></dt>
                            <dd><?=$contractor->display( 'contact_name' )?></dd>
                        </dl>

                        <dl>
                            <dt><?=$rc->pod_data['fields']['phone_number']['label']?></dt>
                            <dd><?=$contractor->display( 'phone_number' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['email_address']['label']?></dt>
                            <dd><?=$contractor->display( 'email_address' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['web_address']['label']?></dt>
                            <dd><?=$contractor->display( 'web_address' )?></dd>
                        </dl>
                    <?php
                    endif;
                    endwhile;
                    ?>

                </div>
                <div class="tab-pane well well-sm" id="business-other">
                    <h3>Other Information:</h3>
                    <?php
                    while ( $contractor_oth->fetch() ):?>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['roofing_service']['label']?></dt>
                            <dd><?=$contractor_oth->display( 'roofing_service' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['type_of_services']['label']?></dt>
                            <dd><?=$contractor_oth->display( 'type_of_services' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['business_more_than_one_state']['label']?></dt>
                            <dd><?=$contractor_oth->display( 'business_more_than_one_state')?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['labour_crews']['label']?></dt>
                            <dd><?=$contractor_oth->display( 'labour_crews' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['hold_osha_certification']['label']?></dt>
                            <dd><?=$contractor_oth->display( 'hold_osha_certification' )?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['business_referrals']['label']?></dt>
                            <dd>
                                <?php
                                if($contractor_oth->display( 'business_referrals' )):
                                    foreach ($contractor_oth->field( 'business_referrals' , null, true  ) as $key => $ref): ?>
                                        <ul class="well well-sm">
                                            <li class=""><strong>Name:</strong>  <?=$ref['ref_name']?></li>
                                            <li class=""><strong>Email:</strong>  <?=$ref['ref_email']?></li>
                                            <li class=""><strong>Phone Number:  </strong><?=$ref['ref_phone_number']?></li>
                                        </ul>
                                    <?php
                                    endforeach;;
                                else:
                                    echo "Not mentioned!";
                                endif;
                                ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['business_information']['label']?></dt>
                            <dd><?=$contractor_oth->display( 'business_information')?></dd>
                        </dl>
                        <dl>
                            <dt><?=$rc->pod_data['fields']['interested_in_advertising']['label']?></dt>
                            <dd><?=$contractor_oth->display( 'interested_in_advertising' )?></dd>
                        </dl>
                    <?php
                        //print_r($contractor_oth);
                    endwhile;
                    ?>
                </div>
            </div>
        </div><?php
        endif;
    endif;
    ?>
</div>

<?php
//print_r($_GET);
if(isset($_GET['display']) && $_GET['display'] == 'view'):
    if(isset($_GET['rid']) && $_GET['rid']):
        $id = encrypt_decrypt('decrypt',$_GET['rid']);
        ?>
        <script type="application/javascript">
            jQuery(document).ready(function () {
                jQuery.ajax({
                    method: "POST",
                    url: etajaxurl,
                    data: {
                        fed_ajax_hook: "roofing_view",
                        id: '<?=$_GET['rid']?>'
                    },
                    dataType: 'json',
                    success: function (data) {
                        jQuery('.js-table-content').hide(100);
                        jQuery('div.js-content-body').html(data.html);
                        jQuery('div.js-content-body').show();

                    }
                });
            });
        </script>
    <?php
    endif;
endif;
?>



<div class="row" <?= $bidstyle ?>>
    <?php
    if (isset($_GET['display']) && $_GET['display'] == 'bid'):
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
        $params = array(
            'where' => ' author.ID != "'.$userinfo->ID.'" AND is_read = 0 AND roofing_request.id = ' . $rrc->display('id'),
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

                            if($contractor['ID'] != $userinfo->ID):
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
                                                    <div class="comment-user"><i class="fa fa-user"></i> <?= $con->display('name'); ?></div>
                                                    <time class="comment-date" datetime="<?=$bcoms->display('created')?>"><i class="fa fa-clock-o"></i> <?=$bcoms->display('created')?></time>
                                                </header>
                                                <div class="comment-post">
                                                    <p>
                                                        <?=nl2br($bcoms->display('comments'))?>
                                                    </p>
                                                </div>
                                                <?php if($bcoms->display('price') != "$0.00"):?><p class="text-right"> Offered Price <a class="btn btn-danger btn-sm"><?=$bcoms->display('price')?></a> <?php if($bcoms->display('price_accept') == 'Yes'):?> <span class="label label-success">Accepted</span><?php else:?><a class="btn btn-sm btn-success js-accept-offer"  data-comment="<?=$bcoms->display('id')?>" data-con="<?=$contractor['ID']?>" data-rq="<?=$rrc->display('id')?>">Accept</a><?php endif;?></p><?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 hidden-xs">
                                        <figure class="thumbnail">
                                            <img class="img-responsive img-circle" src="<?=no_image_url()?>" />
                                            <figcaption class="text-center"><?= $con->display('name'); ?></figcaption>
                                        </figure>
                                    </div>
                                </article>

                            <?php
                            else:

                                ?>
                                <!-- First Comment -->
                                <article class="row">
                                    <div class="col-md-2 col-sm-2 hidden-xs">
                                        <figure class="thumbnail">
                                            <img class="img-responsive img-circle" src="<?=no_image_url()?>" />
                                            <figcaption class="text-center">Me (<?= $rrc->display('first_name'); ?> <?= $rrc->display('last_name'); ?>)</figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-md-10 col-sm-10">
                                        <div class="panel panel-default arrow left">
                                            <div class="panel-body">
                                                <header class="text-left">
                                                    <div class="comment-user"><i class="fa fa-user"></i> <?= $rrc->display('first_name'); ?> <?= $rrc->display('last_name'); ?></div>
                                                    <time class="comment-date" datetime="<?=$bcoms->display('created')?>"><i class="fa fa-clock-o"></i> <?=$bcoms->display('created')?></time>
                                                </header>
                                                <div class="comment-post">
                                                    <p>
                                                        <?=nl2br($bcoms->display('comments'))?>
                                                    </p>
                                                </div>
                                                <?php if($bcoms->display('price') != "$0.00"):?><p class="text-right"> Counter Price <a class="btn btn-danger btn-sm"><?=$bcoms->display('price')?></a></p><?php endif;?>
                                            </div>
                                        </div>
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
                        <label for="inputEmail3" class="col-sm-2 control-label">Counter Price</label>
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
    <?php
    endif;
    ?>
</div>


<div class="row" <?= $bidliststyle ?>>
    <?php
    if (isset($_GET['display']) && $_GET['display'] == 'bidlist'):
        $id = encrypt_decrypt('decrypt', $_GET['rlid']);
        $rc = pods('roofhub_request');
        $data = [];

        $params = array(
            'where' => 't.id = ' . $id,
            'limit' => 1  // Return all rows
        );
        $rrc = pods('roofhub_request', $params);

        $userinfo = wp_get_current_user();


        $bc = $rrc->field('bidding_contractors', null, true);
        $ac = $rrc->field('assign_contractors', null, true);
        ?>
        <h3>Your bidding contractor lists</h3>
        <div class="">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Registered Business Name</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Zip</th>
                        <th>Contact Name</th>
                        <th>Contact Phone</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($bc):
                        foreach ($bc as $bkey => $b):
                            $params = array(
                                'where'   => 'author.ID = '.$b['ID'],
                                'limit'   => 1  // Return all rows
                            );
                            $contractor = pods( 'contractor', $params );
                            $bidlist = site_url("/dashboard/")."?menu_type=user&menu_slug=your_roofing_requests&display=bid&reqid=".encrypt_decrypt('encrypt',$rrc->display( 'id' ));

                            $params = array(
                                'where' => ' author.ID = "'.$b['ID'].'" AND is_read = 0 AND roofing_request.id = ' . $rrc->display('id'),
                                'orderby' => 't.id ASC',
                                'limit' => -1  // Return all rows
                            );
                            $bcoms = pods('bidding_comment', $params);
                    ?>
                            <tr>
                                <td><?=$contractor->display('name');?></td>
                                <td><?=$contractor->display('state');?></td>
                                <td><?=$contractor->display('city');?></td>
                                <td><?=$contractor->display('zip_code');?></td>
                                <td><?=$contractor->display('contact_name');?></td>
                                <td><?=$contractor->display('phone_number');?></td>
                                <td><a class="btn btn-xs btn-danger" href="<?=$bidlist?>" target="_blank">View Comments</a> <?php if (0 < $bcoms->total()):?><span class="label label-primary"><?=$bcoms->total()?> New Message</span><?php endif;?></td>
                            </tr>
                    <?php
                        endforeach;
                    endif;
                    ?>
                    <?php
                    if($ac):
                        foreach ($ac as $bkey => $a):
                            $params = array(
                                'where'   => 'author.ID = '.$a['ID'],
                                'limit'   => 1  // Return all rows
                            );
                            $contractor = pods( 'contractor', $params );
                            $bidlist = site_url("/dashboard/")."?menu_type=user&menu_slug=your_roofing_requests&display=bid&reqid=".encrypt_decrypt('encrypt',$rrc->display( 'id' ));

                            $params = array(
                                'where' => ' author.ID = "'.$a['ID'].'" AND is_read = 0 AND roofing_request.id = ' . $rrc->display('id'),
                                'orderby' => 't.id ASC',
                                'limit' => -1  // Return all rows
                            );
                            $bcoms = pods('bidding_comment', $params);
                            ?>
                            <tr>
                                <td><?=$contractor->display('name');?></td>
                                <td><?=$contractor->display('state');?></td>
                                <td><?=$contractor->display('city');?></td>
                                <td><?=$contractor->display('zip_code');?></td>
                                <td><?=$contractor->display('contact_name');?></td>
                                <td><?=$contractor->display('phone_number');?></td>
                                <td><a class="btn btn-xs btn-danger" href="<?=$bidlist?>" target="_blank">View Comments</a> <?php if (0 < $bcoms->total()):?><span class="label label-primary"><?=$bcoms->total()?> New Message</span><?php endif;?></td>
                            </tr>
                        <?php
                        endforeach;
                    endif;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    endif;
    ?>

</div>
