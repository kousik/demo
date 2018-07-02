<?php
/**
    Template Name: Sponsor Landing Page
 */

// Add our custom loop
add_action( 'genesis_after_loop', 'cd_goh_loop' );
function cd_goh_loop() {
    /*$params = array(
        'orderby' => 't.duration ASC',
        'limit'   => -1  // Return all rows
    );

    $packages = pods( 'adv_package', $params );
    $duration = ['free', 'month', 'year', 'lifetime']*/
    ?>
    <!--<div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h2>Select your sponsor package - </h2>
        </div>
    </div>-->
    <div class="row">
    <?php
        /*if ( 0 < $packages->total() ):
            $tot = $packages->total();
            $i = 1;
            while ( $packages->fetch() ):
                $raw_data = $packages->data();
                $raw_data = $raw_data[$i-1];
                if($tot == 1):
                    $col_class = "col-md-offset-5";
                elseif ($tot >1):
                    $num = (5-($tot-1));
                    $col_class = "col-md-offset-{$num}";
                else:
                    $col_class = "";
                endif;
                */?><!--

                <div class="col-md-2 <?/*=($i == 1)?$col_class:""*/?>">
                    <div class="thumbnail">
                        <div class="well well-lg" style="background-color: #fb9635 !important;">
                            <h2 class="text-center"><?/*=$packages->display('name');*/?></h2>
                            <h2 class="text-center"><?/*=$packages->display('price');*/?></h2>
                        </div>
                        <div class="caption">
                            <h3 class="text-center"><?/*=$packages->display('name');*/?></h3>
                            <p class="text-justify"><?/*=$packages->display('description');*/?></p>
                            <?php
/*                            if(is_user_logged_in() && current_user_can( 'sponsor' )):
                                $adv_info = get_sponsor_adinfo();
                                if($adv_info['remain'] && ($i == 1)):

                            */?>
                                <p class="text-center"><a href="<?/*=site_url('sponsor/add/new/').encrypt_decrypt('encrypt', $raw_data->id)*/?>"><button type="button" class="scf-button" style="width: 100%;">DEAL</button></a></p>
                           <?php
/*                                elseif(!$adv_info['remain'] && ($i == 1)):
                                    */?>
                                    <p class="text-center"><a href="javascript://"><button type="button" class="scf-button" style="width: 100%;">NOT AAVAILABLE</button></a></p>
                                <?php
/*                                else:
                                    $ad_id = '';
                                    if(isset($_GET['ad-id']) && $_GET['ad-id']):
                                        $ad_id = '?ad-id='.$_GET['ad-id'];
                                    endif;
                                    */?>
                                    <p class="text-center"><a href="<?/*=site_url('sponsor/payment/').encrypt_decrypt('encrypt', $raw_data->id).$ad_id*/?>"><button type="button" class="scf-button" style="width: 100%;">DEAL</button></a></p>
                                <?php
/*                                endif;

                            elseif(!is_user_logged_in()):
                                */?>
                                <p class="text-center"><a href="<?/*=site_url('sign-in')*/?>?redirect_to=<?/*=site_url('sponsor/payment/').encrypt_decrypt('encrypt', $raw_data->id)*/?>"><button type="button" class="scf-button" style="width: 100%;">DEAL</button></a></p>
                            <?php
/*                            endif;
                           */?>
                        </div>
                    </div>
                </div>
                --><?php
/*            $i++;
            endwhile;
        endif;*/
            ?>


    </div>

    <div class="row">
        <h2 style="margin-left:2%;">Visit our sponsor page - </h2>
        <div class="col-md-4 col-md-offset-2">
            <a href="<?=get_page_link(251)?>"><button type="button" class="scf-button" style="width: 100%;">Premier Sponsors</button></a>
        </div>
        <div class="col-md-4">
            <a href="<?=get_page_link(248)?>"><button type="button" class="scf-button"  style="width: 100%;">Sponsors</button></a>
        </div>
    </div>

    <?php
}

genesis();