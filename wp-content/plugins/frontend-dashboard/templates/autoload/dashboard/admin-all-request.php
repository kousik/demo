<?php

$params = array(
    'limit'   => -1  // Return all rows
);

// Create and find in one shot
$rf_requests = pods( 'roofhub_request', $params );
?>
    <div class="response" style="display: none;"></div>
    <div class="table-responsive js-table-content">
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

                            ?>
                            <a class="btn btn-info js-view-data" data-action="roofing_view" data-req="<?=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))?>" href="javascript://" role="button" alt="View" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <a class="btn btn-warning js-view-data" data-action="roofing_edit" data-req="<?=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))?>" href="javascript://" role="button" alt="Assign" title="Assign"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a class="btn btn-danger js-request-delete" href="javascript://" role="button" alt="Delete" data-req="<?=encrypt_decrypt('encrypt',$rf_requests->display( 'id' ))?>" title="Delete"><i class="fa fa-close" aria-hidden="true"></i></a>
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
<div class="js-content-body" style="display: none;">

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