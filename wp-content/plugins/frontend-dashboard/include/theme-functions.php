<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 30/5/18
 * Time: 9:36 PM
 */

add_action('genesis_after_header', 'fdb_add_header_content', 10);

function fdb_add_header_content(){
    ?>
    <div class="befor-content">
        <div class="row">
            <div class="col-xs-12">
                <p class="text-left">You are visitor number <?=do_shortcode("[ads-wpsitecount image=flippingnumbers.jpg imgmaxw='100' width=100 whunit='px' height=0 block='' ]")?><!-- &nbsp;&nbsp;Current State : --><?/*=$_COOKIE['state']*/?></p>
            </div>
        </div>
    </div>
<?php
}


add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

function wti_loginout_menu_link( $items, $args ) {
    if ($args->theme_location == 'primary') {
        if (is_user_logged_in()) {
            $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1111"><a href="'. site_url('dashboard') .'">'. __("My Account") .'</a></li>';
            $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1112"><a href="'. wp_logout_url( home_url() ) .'">'. __("Log Out") .'</a></li>';
        } else {
            $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1113"><a href="'. site_url('sign-in') .'">'. __("Log In") .'</a></li>';
            $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1114"><a href="'. site_url('sign-up') .'">'. __("Sign Up") .'</a></li>';
        }
    }
    return $items;
}


add_shortcode('ad', 'get_ad_short_code');
//$id = false, $type = false, $limit = false, $location = false, $slide = false, $pagination = false
function get_ad_short_code($atts, $content = null){
// Attributes
    $atts = shortcode_atts(
        array(
            'id' => '',
            'type' => '',
            'limit' => 12,
            'location' => '',
            'slide' => '',
            'pagination' => ''
        ),
        $atts,
        'ad'
    );


    if($atts['id']):
        $params = array(
            'where'   => 't.id = '.$atts['id'],
            'limit'   => 1  // Return all rows
        );
        $mypod = pods( 'advertisement', $params );

        ob_start();
        while ( $mypod->fetch() ) {
            ?>
            <div class="row">
            <div class="item col-md-4 single-pod">
                <div class="thumbnail">
                    <?php
                    $image = no_image_url();
                    if($mypod->display('sponsor_image')):
                        $image = $mypod->display('sponsor_image');
                    endif;?>
                    <a href="<?=$mypod->display('url_link');?>" target="_blank"><img src="<?=$image;?>" class="img-thumbnail" style="height: 380px; width: 640px;"></a>
                    <div class="caption">
                        <h3 class="text-center"><?=$mypod->display('name');?></h3>
                        <!--<span class="text-center" style="max-height: 100px; min-height: 50px;"><?/*=$pod->display('description');*/?></span>-->
                        <p class="text-center"><a href="<?=$mypod->display('url_link');?>" target="_blank"><input type="submit" id="scf-button" value="Visit Now"></a></p>
                    </div>
                </div>
            </div>
            </div>
            <?php
        }

        $data = ob_get_contents();
        ob_clean();
        return $data;
    endif;




    if($atts['type'] && !$atts['slide']):
        $where = ' ';
        if($atts['type'] == 'premier'):
            $where .= ' AND t.is_premium = "1" ';
        else:
            $where .= ' AND t.is_premium = "0" ';
        endif;
        $params = array(
            'where'   => 't.is_delete = "0" AND t.status = "1" '.$where,
            'limit'   => $atts['limit']  // Return all rows
        );

        if($atts['pagination'] == 1):
            $params['orderby'] = 'created DESC';
        else:
            $params['orderby'] = 'RAND()';
        endif;

        $mypod = pods( 'advertisement', $params );

        ob_start();
        ?>
        <div class="row">
        <?php
        while ( $mypod->fetch() ) {
            ?>
            <div class="item col-md-4 single-pod">
                <div class="thumbnail">
                    <?php
                    $image = no_image_url();
                    if($mypod->display('sponsor_image')):
                        $image = $mypod->display('sponsor_image');
                    endif;?>
                    <a href="<?=$mypod->display('url_link');?>" target="_blank"><img src="<?=$image;?>" class="img-thumbnail" style="height: 380px; width: 640px;"></a>
                    <div class="caption">
                        <h3 class="text-center"><?=$mypod->display('name');?></h3>
                        <!--<span class="text-center" style="max-height: 100px; min-height: 50px;"><?/*=$pod->display('description');*/?></span>-->
                        <p class="text-center"><a href="<?=$mypod->display('url_link');?>" target="_blank"><input type="submit" id="scf-button" value="Visit Now"></a></p>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        </div>
        <?php if($atts['pagination'] == 1):?>
        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center">
                <?php
                // Advanced Pagination
                echo $mypod->pagination( array( 'type' => 'advanced' ) );
                ?>
            </div>
        </div>
        <style>


            @media (min-width: 992px) {
                div.tem{
                    height: 600px;
                }
            }

            .pods-pagination-advanced  {
                display: inline-block;
            }

            .pods-pagination-advanced  a , span.pods-pagination-number,  span.pods-pagination-current  {
                color: black;
                float: left;
                padding: 8px 16px;
                text-decoration: none;
                border: 1px solid #ddd;
            }

            .pods-pagination-advanced  span.pods-pagination-current {
                background-color: #fb9635;
                color: white;
                border: 1px solid #fb9635;
            }

            .pods-pagination-advanced  a:hover:not(.active) {background-color: #ddd;}

            .pods-pagination-advanced  a:first-child {
                border-top-left-radius: 5px;
                border-bottom-left-radius: 5px;
            }

            .pods-pagination-advanced  a:last-child {
                border-top-right-radius: 5px;
                border-bottom-right-radius: 5px;
            }
        </style>
    <?php endif;?>
       <?php

        $data = ob_get_contents();
        ob_clean();
        return $data;
    endif;


    if($atts['type'] && $atts['slide']):
        $where = ' ';
        if($atts['type'] == 'premier'):
            $where .= ' AND t.is_premium = "1" ';
        else:
            $where .= ' AND t.is_premium = "0" ';
        endif;
        $params = array(
            'where'   => 't.is_delete = "0" AND t.status = "1" '.$where,
            'limit'   => $atts['limit'],  // Return all rows
            'orderby' => 'RAND()'
        );
        $mypod = pods( 'advertisement', $params );

        ob_start();
        ?>
        <div class="row">
        <div class="ad-slide-main" style="max-width: 545px;">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $i = 1;
                    while ( $mypod->fetch() ) {
                        ?>
                        <div class="item <?=$i==1?'active':''?> col-md-12 single-pod">
                            <div class="thumbnail">
                                <?php
                                $image = no_image_url();
                                if($mypod->display('sponsor_image')):
                                    $image = $mypod->display('sponsor_image');
                                endif;?>
                                <a href="<?=$mypod->display('url_link');?>" target="_blank"><img src="<?=$image;?>" class="img-thumbnail" style="height: 380px; width: 640px;"></a>
                                <div class="carousel-caption">
                                    <h3 class="text-center"><?=$mypod->display('name');?></h3>
                                    <!--<span class="text-center" style="max-height: 100px; min-height: 50px;"><?/*=$pod->display('description');*/?></span>-->
                                    <p class="text-center"><a href="<?=$mypod->display('url_link');?>" target="_blank"><input type="submit" id="scf-button" value="Visit Now"></a></p>
                                </div>
                            </div>
                        </div>
                        <?php $i++;
                    }
                    ?>
                </div>
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>
        </div>
        </div>
    <script type="application/javascript">
        jQuery(document).ready(function () {
            jQuery('.carousel').carousel({
                interval: 5000
            });
        });
    </script>
        <?php

        $data = ob_get_contents();
        ob_clean();
        return $data;
    endif;

}


add_shortcode('contractor-slide', 'get_cslide_short_code');
//$id = false, $type = false, $limit = false, $location = false, $slide = false, $pagination = false
function get_cslide_short_code($atts, $content = null){
// Attributes
    $atts = shortcode_atts(
        array(
            'slide' => true
        ),
        $atts,
        'contractor-slide'
    );

    if($atts['slide']):

        $params = array(
            'where'   => 't.featured_contractor = 1 AND t.status = "1" ',
            'limit'   => 20,  // Return all rows
            'orderby' => 'RAND()'
        );
        $mypod = pods( 'contractor', $params );

        ob_start();
        ?>
        <div class="row">
            <div class="ad-slide-main">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $count = 0;
                        $i = 1;
                        while ( $mypod->fetch() ) {
                            ?>
                            <?php
                            $image = "http://roofhub.com/wp-content/uploads/2018/05/cropped-RoofHub680.png";
                            if($mypod->display('company_logo')):
                                $image = $mypod->display('company_logo');
                            endif;?>
                            <div class="item <?=$i==1?'active':''?> col-md-12 single-pod">
                                <div class="thumbnail">
                                    <a href="javascript://" target="_blank"><img src="<?=$image?>" class="img-thumbnail"></a>
                                    <div class="caption">
                                        <h1 class="text-center" style="font-weight: bold; color: #000000;"><?=ucfirst($mypod->display('name'));?></h1>
                                        <p class="text-center" style="max-height: 100px; min-height: 50px; color: #000000;"><strong>Reg./Lc. NO. - </strong><?=$mypod->display('registry_or_license_number');?></p>
                                        <h1 class="text-center" style="font-weight: bold;color: #ff9543;margin-top: 15px;margin-bottom: 10px color: #000000;"><?=$mypod->display('state');?></h1>
                                    </div>
                                </div>
                            </div>

                            <?php $count++;$i++;
                        }
                        ?>
                    </div>
                    <!--<a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>-->

                </div>
            </div>
        </div>
        <script type="application/javascript">
            jQuery(document).ready(function () {
                jQuery('.carousel').carousel({
                    interval: 5000
                });
            });
        </script>
        <?php

        $data = ob_get_contents();
        ob_clean();
        return $data;
    endif;

}


add_shortcode('agent', 'get_agent_short_code');
function get_agent_short_code($atts, $content = null){
// Attributes
    $atts = shortcode_atts(
        array(
            'display' => true
        ),
        $atts,
        'agent'
    );

    if($atts['display']):
        wp_enqueue_script( 'rap-js', plugins_url('/_inc/tparty/map/raphael-min.js', BC_FED_PLUGIN), array( 'jquery' ) );
        wp_enqueue_script( 'usmap-js', plugins_url('/_inc/tparty/map/jquery.usmap.js', BC_FED_PLUGIN), array( 'jquery' ) );

        ob_start();
        ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <div id="clicked-state"></div>
                <div id="wmap" style="width: 450px; height: 300px; float: right;"></div>
            </div>
        </div>
        <script type="application/javascript">
            jQuery(document).ready(function() {
                var mapsdata = jQuery.parseJSON('{"AL":"Alabama","AK":"Alaska","AS":"American Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District Of Columbia","FM":"Federated States Of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","MP":"Northern Mariana Islands","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Islands","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}');
                jQuery('#wmap').usmap({
                    showLabels: true,
                    stateHoverStyles: {fill: 'red'},
                    stateSpecificStyles: {
                        "AL": {fill: 'orange'},
                        "AK": {fill: 'Brown'},
                        "AS": {fill: 'Coral'},
                        "AZ": {fill: 'Crimson'},
                        "AR": {fill: 'DarkGreen'},
                        "CA": {fill: 'Chartreuse'},
                        "CO": {fill: 'DarkTurquoise'},
                        "CT": {fill: 'DodgerBlue'},
                        "DE": {fill: 'Gold'},
                        "DC": {fill: 'Fuchsia'},
                        "FM": {fill: 'LightSlateGray'},
                        "FL": {fill: 'Olive'},
                        "GA": {fill: 'PaleGoldenRod'},
                        "GU": {fill: 'Salmon'},
                        "HI": {fill: 'SandyBrown'},
                        "ID": {fill: 'SeaGreen'},
                        "IL": {fill: 'SlateBlue'},
                        "IN": {fill: 'Yellow'},
                        "IA": {fill: 'Navy'},
                        "KS": {fill: 'Gold'},
                        "KY": {fill: 'Lime'},
                        "LA": {fill: 'Brown'},
                        "ME": {fill: 'Coral'},
                        "MH": {fill: 'Crimson'},
                        "MD": {fill: 'DarkGreen'},
                        "MA": {fill: 'Chartreuse'},
                        "MI": {fill: 'DarkTurquoise'},
                        "MN": {fill: 'DodgerBlue'},
                        "MS": {fill: 'Gold'},
                        "MO": {fill: 'Fuchsia'},
                        "MT": {fill: 'LightSlateGray'},
                        "NE": {fill: 'Olive'},
                        "NV": {fill: 'PaleGoldenRod'},
                        "NH": {fill: 'SandyBrown'},
                        "NJ": {fill: 'SeaGreen'},
                        "NM": {fill: 'SlateBlue'},
                        "NY": {fill: 'Yellow'},
                        "NC": {fill: 'Navy'},
                        "ND": {fill: 'Gold'},
                        "MP": {fill: 'Lime'},
                        "OH": {fill: 'Brown'},
                        "OK": {fill: 'Coral'},
                        "OR": {fill: 'Crimson'},
                        "PW": {fill: 'DarkGreen'},
                        "PA": {fill: 'Chartreuse'},
                        "PR": {fill: 'DarkTurquoise'},
                        "RI": {fill: 'DodgerBlue'},
                        "SC": {fill: 'Gold'},
                        "SD": {fill: 'Fuchsia'},
                        "TN": {fill: 'LightSlateGray'},
                        "TX": {fill: 'Olive'},
                        "UT": {fill: 'PaleGoldenRod'},
                        "VT": {fill: 'purple'},
                        "VI": {fill: 'purple'},
                        "VA": {fill: 'purple'},
                        "WA": {fill: 'purple'},
                        "WV": {fill: 'purple'},
                        "WI": {fill: 'purple'},
                        "WY": {fill: 'purple'}
                    },
                    click: function(event, data) {
                        jQuery('#clicked-state')
                            .text('You clicked: '+data.name)
                            .css({'color':'#000', 'font-weight': 'bold'});

                        //var url = "<?=get_permalink(get_the_ID())?>";
                        window.location.href = '<?=site_url('/contractors/')?>'+'?state='+mapsdata[data.name];
                    }
                });
            });
        </script>
        <?php

        $data = ob_get_contents();
        ob_clean();
        return $data;
    endif;

}

add_action( 'genesis_footer', 'genesis_link_footer',1 );

function genesis_link_footer(){
    ?>
    <style>
        .pfooter {
            /*position: fixed;*/
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #282a2a;
            color: white;
            text-align: center;
        }
        .pfooter a { color: #FFFFFF;}
        .pfooter a:hover { color: #e85555;}
    </style>

    <div class="pfooter">
        <p><a href="<?=site_url('terms-and-conditions')?>">Terms & Conditions</a> | <a href="<?=site_url('privacy-policy')?>">Privacy Policy</a></p>
    </div>
<?php
}