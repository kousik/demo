<?php
/**
    Template Name: Sponsor Listing Page
 */

// Add our custom loop
add_action( 'genesis_after_loop', 'cd_goh_loop' );
function cd_goh_loop() {
    global $page;

    //CSS
    //wp_register_style('fdb_owl_css', plugins_url('/_inc/tparty/owl/assets/owl.carousel.css',  BC_FED_PLUGIN));
    //wp_enqueue_style('fdb_owl_css');
    //wp_enqueue_script( 'fdb-owl-js', plugins_url('/_inc/tparty/owl/owl.carousel.min.js', BC_FED_PLUGIN), array( 'jquery' ) );
    $pod = pods( 'advertisement' );
    $where = ' ';
    if(get_the_ID() == 251):
        $where .= ' AND t.is_premium = "1" ';
    else:
        $where .= ' AND t.is_premium = "0" ';
    endif;

    $st = false;
    if(isset($_GET['state']) && $_GET['state'] != ''):

        if(get_the_ID() == 251):
            $where .= ' OR t.state = "'. $_GET['state'].'" ';
        else:
            $where .= ' AND t.state = "'. $_GET['state'].'" ';
        endif;
        $st = $_GET['state'];
    endif;

    $params = array(
            'where' => 't.is_delete = "0" AND t.status = "1" '.$where,
            'limit' => 15
    );
    $pod->find( $params );
    ?>
    <div class="row">
        <div class="col-md-4">
           <h3>Sponsors on <?=$st?$st." State":"All States"?></h3>
        </div>
    </div>

    <?php if(!$st):
        wp_enqueue_script( 'rap-js', plugins_url('/_inc/tparty/map/raphael-min.js', BC_FED_PLUGIN), array( 'jquery' ) );
        wp_enqueue_script( 'usmap-js', plugins_url('/_inc/tparty/map/jquery.usmap.js', BC_FED_PLUGIN), array( 'jquery' ) );
        ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <div id="clicked-state"></div>
                <div id="map" style="width: 75%; height: 600px; float: right;"></div>
            </div>
        </div>
    <script type="application/javascript">
        jQuery(document).ready(function() {
            var mapsdata = jQuery.parseJSON('{"AL":"Alabama","AK":"Alaska","AS":"American Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District Of Columbia","FM":"Federated States Of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","MP":"Northern Mariana Islands","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Islands","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}');
            jQuery('#map').usmap({
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

                    var url = "<?=get_permalink(get_the_ID())?>";
                    window.location.href = url+'?state='+mapsdata[data.name];
                }
            });
        });
    </script>

    <?php else:?>
    <div class="row">
        <div id="owl-demo" class="owl-carousel owl-theme">
            <?php
            if ( 0 < $pod->total() ):
                $tot = $pod->total();
                $i = 1;
                while ( $pod->fetch() ):
                ?>
                <div class="item col-md-4">
                    <div class="thumbnail">
                        <?php
                        $image = no_image_url();
                        if($pod->display('sponsor_image')):
                            $image = $pod->display('sponsor_image');
                        endif;?>
                        <a href="<?=$pod->display('url_link');?>" target="_blank"><img src="<?=$image;?>" class="img-thumbnail" style="height: 380px; width: 640px;"></a>
                        <div class="caption">
                            <h3 class="text-center"><?=$pod->display('name');?></h3>
                            <!--<span class="text-center" style="max-height: 100px; min-height: 50px;"><?/*=$pod->display('description');*/?></span>-->
                            <p class="text-center"><a href="<?=$pod->display('url_link');?>" target="_blank"><input type="submit" id="scf-button" value="Visit Now"></a></p>
                        </div>
                    </div>
                </div>
                <?php
                endwhile;
            else:
            ?>
            <div class="col-md-6 col-md-offset-3 text-center">
                <div class="alert alert-warning" role="alert">Oops! No Sponsor found!</div>
            </div>
            <?php
            endif;
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">
            <?php
            // Advanced Pagination
            echo $pod->pagination( array( 'type' => 'advanced' ) );
            ?>
        </div>
    </div>
   <?php endif;?>



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

    <?php
}

add_action('genesis_before_content', 'fbd_state_drop_down', 10);

function fbd_state_drop_down(){
    global $page;
    $pg = '';
    if(isset($_GET['pg']) && $_GET['pg'] != ''):
        $pg = $_GET['pg'];
    endif;

    $state = '';
    if(isset($_GET['state']) && $_GET['state'] != ''):
        $state .= $_GET['state'];
    endif;
    ?>

    <div class="row">
        <div class="col-md-3 col-md-offset-9">
            <span style="display: inline-flex;">Select state: &nbsp; <select data-placeholder="Choose a State..." class="chosen-select js-url-state form-control" name="state" tabindex="2" style="width: 250px !important;" data-url="<?=get_permalink(get_the_ID())?>" data-pg="<?=$pg?>">
                                                <option value=""> All States</option>
                    <?php foreach (fdb_get_state() as $skey => $sobj):?>
                        <option value="<?=$sobj->name?>" <?=$state==$sobj->name?'selected':''?> ><?=$sobj->name?></option>
                    <?php endforeach;?>
                                            </select>
            </span>
        </div>
    </div>
<?php
}

genesis();