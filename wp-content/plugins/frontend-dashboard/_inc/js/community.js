/*!
 
 * jQuery OAuth via popup window plugin
 
 *
 
 * @author  Nobu Funaki @nobuf
 
 *
 
 * Dual licensed under the MIT and GPL licenses:
 
 *   http://www.opensource.org/licenses/mit-license.php
 
 *   http://www.gnu.org/licenses/gpl.html
 
 */

(function ($) {
    //  inspired by DISQUS
    $.oauthpopup = function (options)
    {
        if (!options || !options.path) {
            throw new Error("options.path must not be empty");
        }
        options = $.extend({
            windowName: 'ConnectWithOAuth' // should not include space for IE
            , windowOptions: 'location=0,status=0,width=800,height=400'
            , callback: function () {
                window.location.reload();
            }
        }, options);

        // var oauthWindow   = window.open(options.path, options.path, options.windowOptions);
        var oauthWindow = popupwindow(options.path, options.path, 800, 400);
        var oauthInterval = window.setInterval(function () {
            if (oauthWindow.closed) {
                window.clearInterval(oauthInterval);
                options.callback();
            }
        }, 1000);
    };

    function popupwindow(url, title, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        return window.open(url, title, 'location=no, status=no,  width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }

    //bind to element and pop oauth when clicked
    $.fn.oauthpopup = function (options) {
        $this = $(this);
        $this.click($.oauthpopup.bind(this, options));
    };
})(jQuery);


var jq = jQuery;
var redirect_uri = sessionStorage.getItem('redirect_uri');
var current_url = location.href;
var auth_msg = 0;

// Create Base64 Object
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}


jq(document).ready(function () {


    jq("body").delegate("form#etf-hub-form input[name='etf-hub-form-submit'], form#etf-hub-form button[name='etf-hub-form-submit']", "click", function (e) {

        // submit the form 
        var form = jq(this);
        do {
            form = form.parent();
        }
        while (!form.is("form"));
        etf_hub_form(form);

        // return false to prevent normal browser submit and page navigation 
        return false;
    });



    jq("form#etf-hub-form button[name='rf-form-submit'], form#etf-hub-form-rf button[name='rf-form-submit'], form#etf-hub-form-rf input[name='rf-form-submit']").click(function(e){

        // submit the form
        var form = jq(this);
        do {
            form = form.parent();
        }
        while (!form.is("form"));
        etf_hub_form(form);

        // return false to prevent normal browser submit and page navigation
        return false;
    });

    jq(".js-profile-state").chosen();
    jq(".js-profile-city").chosen();
    jq(".js-profile-state").change(function(){
        jq.post(etajaxurl, {
            state_id: jq(this).val(),
            fed_ajax_hook: "get_city_data"
        }, function (data) {
            jq('.js-profile-city').empty();
            jq('.js-profile-city').html(data);
            //jq(".js-city").chosen("destroy");
            jq(".js-profile-city").trigger("chosen:updated");
        }, "html");
    });



    //Roofing
    jq(".js-state").chosen();
    jq(".js-city").chosen();
    //jQuery time
    var current_fs, next_fs, previous_fs, current_ct = 1; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches
    var email_validate = false;

    jq(".next").click(function(){


        if(current_ct == 1) {
            jq("div.etf-community-ajax-feedback").empty();
            if (!jq('.js-state').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select your state!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }


        if(current_ct == 2) {
            if (!jq('.js-city').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select your city!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }


        if(current_ct == 3) {
            if (jq('input[name=building_type]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 4) {

            if (jq('input[name=building_level]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }

        }

        if(current_ct == 5) {

            if (jq('input[name=roof_type]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }

        }

        if(current_ct == 6) {

            if (jq('input[name=property_accessible]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }

        }

        if(current_ct == 7) {

            if (jq('input[name=roof_accessible]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }

        }

        if(current_ct == 8) {

            if (jq('input[name=roof_cond]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            } else {
                if( jq('input[name=roof_cond]:checked').val() == 'Pitched Roof' ){
                    jq('.js-flat-type').hide();
                    jq(".js-pitch-type").show();

                    if (jq('input[name=pitch_type]:checked').length <= 0) {
                        jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                        jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                            setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                        });
                        return false;
                    }


                } else {
                    jq(".js-pitch-type").hide();
                    jq(".js-flat-type").show();

                    if (jq('input[name=flat_type]:checked').length <= 0) {
                        jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                        jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                            setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                        });
                        return false;
                    }
                }


            }

        }

        if(current_ct == 9) {

            if (jq('input[name=visible_water_damage]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }

        }

        if(current_ct == 10) {
            if (jq('input[name=any_skylights]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 11) {
            if (jq('input[name=any_satellite]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 12) {
            if (jq('input[name=visible_damage]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 13) {
            if (jq('input[name=insurance_claim]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            } else {
                if( jq('input[name=insurance_claim]:checked').val() == "Yes" ){
                    jq(".js-ins-info").show();

                    if (!jq('input[name=insurance_provider_name]').val()) {
                        jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Fill up the required fields!</p>');
                        jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                            setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                        });
                        return false;
                    }

                    if (!jq('input[name=claims_agent_name]').val()) {
                        jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Fill up the required fields!</p>');
                        jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                            setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                        });
                        return false;
                    }

                    if (!jq('input[name=claims_agents_phone_number]').val()) {
                        jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Fill up the required fields!</p>');
                        jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                            setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                        });
                        return false;
                    }



                } else {
                    jq(".js-ins-info").hide();
                }
            }
        }

        if(current_ct == 14) {
            if (jq('input[name=time_frame_needed]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }

        }


        if(current_ct == 18) {
            if (!jq('input[name=first_name]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 19) {
            if (!jq('input[name=last_name]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 20) {
            if (!jq('div.rfvalid input[name=email_address]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            } else {
                if (!isEmail(jq('div.rfvalid input[name=email_address]').val())) {
                    jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Please enter valid email!</p>');
                    jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                        setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                    });
                    return false;
                }
            }
        }

        if(current_ct == 21) {
            if (!jq('div.rfvalid input[name=phone_number]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 22) {
            if (!jq('textarea[name=address]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 23) {
            if (!jq('div.rfvalid input[name=zip_code]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        next_fs = jq(this).parent().next();

        //activate next step on progressbar using the index of next_fs
        jq("#progressbar li").eq(jq("fieldset").index(next_fs)).addClass("active");
        current_fs.hide();
        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute'
                });
                next_fs.css({'left': left, 'opacity': opacity});
            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct +1;
    });

    jq(".previous").click(function(){


        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        previous_fs = jq(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq(jq("fieldset").index(current_fs)).removeClass("active");
        current_fs.hide();
        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity, 'position': 'relative'});

            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct - 1;
    });

    jq(".js-state").change(function(){
        jq.post(etajaxurl, {
            state_id: jq(this).val(),
            fed_ajax_hook: "get_city_data"
        }, function (data) {
            jq('.js-city').empty();
            jq('.js-city').html(data);
            //jq(".js-city").chosen("destroy");
            jq(".js-city").trigger("chosen:updated");
        }, "html");
    });


    jq("input[name='roof_cond']").click(function() {
        var test = jq(this).val();
        if( test == 'Pitched Roof' ){
            jq('.js-flat-type').hide();
            jq(".js-pitch-type").show();
        } else {
            jq(".js-pitch-type").hide();
            jq(".js-flat-type").show();
        }
    });

    jq("input[name='insurance_claim']").click(function() {
        var test = jq(this).val();
        if( test == 'Yes' ){
            jq(".js-ins-info").show();
        } else {
            jq(".js-ins-info").hide();
        }
    });

    jq("input[name='pictures_to_upload']").click(function() {
        var test = jq(this).val();
        if( test == 'Yes' ){
            jq(".js-rf-pics").show();
        } else {
            jq(".js-rf-pics").hide();
        }
    });


    jq("input[name='general_contractor']").click(function() {
        var test = jq(this).val();
        if( test == 'Yes' ){
            jq(".js-elv-info").show();
            jq(".js-elv-file").show();
        } else {
            jq(".js-elv-info").hide();
            jq(".js-elv-file").hide();
        }
    });

    jq("a.js-request-delete").click(function() {
        var id = jq(this).attr('data-req');


    });

    jq("body").delegate('.js-request-delete', "click", function(e){
        e.preventDefault();
        if( confirm("Are you sure you want to delete this request?") ){
            var id = $(this).attr("data-req");
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "delete_request",
                    id: id
                },
                dataType: 'json',
                success: function (data) {
                    if(data.delete){
                        jq('.row-'+id).remove();
                    }

                    jq('div.response').html(data.msg);
                    jq("div.response").fadeIn(300,function() { setTimeout( 'jq("div.response").fadeOut(300)', 15000 ); });
                }
            });
        }
    });


    jq('#rfModal').modal({backdrop: 'static', keyboard: false, show: false});
    jq('#rfModal').on('hidden.bs.modal', function (event) {
        location.reload();
    });

    /*function alignModal(){
        var modalDialog = jq(this).find(".modal-dialog");
        /!* Applying the top margin on modal dialog to align it vertically center *!/
        modalDialog.css("margin-top", Math.max(0, (jq(window).height() - modalDialog.height()) / 2));
    }
    // Align modal when it is displayed
    jq(".modal").on("shown.bs.modal", alignModal);

    // Align modal when user resize the window
    jq(window).on("resize", function(){
        jq(".modal:visible").each(alignModal);
    });*/

    //Contractors
    jq('#conModal').modal({backdrop: 'static', keyboard: false, show: false});
    jq('#conModal').on('hidden.bs.modal', function (event) {
        location.reload();
    });

    jq(".js-c-state").chosen();
    jq(".js-c-city").chosen();
    jq(".js-c-state").change(function(){
        jq.post(etajaxurl, {
            state_id: jq(this).val(),
            fed_ajax_hook: "get_contractor_city_data"
        }, function (data) {
            if(data == "not available"){
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">This state not available for you! Choose another please!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
            }

            jq('.js-c-city').empty();
            jq('.js-c-city').html(data);
            //jq(".js-city").chosen("destroy");
            jq(".js-c-city").trigger("chosen:updated");
        }, "html");
    });

    jq("body").delegate("input[name='email_address']", "keyup", function (e) {
        var confemail = jq(this).val();
        jq.post(etajaxurl, {
            email: confemail,
            fed_ajax_hook: "check_valid_email"
        }, function (data) {
            if(data.error) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">'+data.error+'</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                email_validate = true;
            } else {
                email_validate = false;
            }
        }, "json");
    });


    jq("body").delegate(".js-add-html", "click", function (e) {
        e.preventDefault();
        //jq(".js-add-html").click(function(){
        jq.post(etajaxurl, {
            state_id: jq(this).val(),
            fed_ajax_hook: "get_form_html_data"
        }, function (data) {
            jq('.rep-ref').append(data);
        }, "html");
    });

    jq("body").delegate(".js-del-htm", "click", function (e) {
        e.preventDefault();
        //jq(".js-del-htm").click(function () {
        jq(this).parent('div.ref-box').remove();
    });

    jq(".cnext").click(function(){

        if(current_ct == 1) {
            jq("div.etf-community-ajax-feedback").empty();
            if (!jq('.js-c-state').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select your state!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 2) {
            if (!jq('.js-c-city').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select your city!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }


        if(current_ct == 4) {
            if (!jq('input[name=name]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Registered Business Name can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 5) {
            if (!jq('input[name=registry_or_license_number]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Registry of Contractors or License Number can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 6) {
            if (!jq('textarea[name=business_address]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Business Address can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 7) {
            if (!jq('div.convalid input[name=zip_code]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Zip code can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 8) {

            if (!jq('input[name=title]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Title can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }

            if (!jq('input[name=contact_name]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Contact Name can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 9) {
            if (!jq('div.convalid input[name=phone_number]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Phone number can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 10) {
            if (!jq('div.convalid input[name=email_address]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Email Address can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }


            if(email_validate){
                jq('div.convalid input[name=email_address]').val('');
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Please put valid email address!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 11) {
            if (!jq('input[name=web_address]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Web address can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }



        if(current_ct == 13) {
            if (jq('input[name=roofing_service]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 14) {
            if (jq('input[class=type_of_services]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 15) {
            if (jq('input[name=business_more_than_one_state]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 16) {
            if (jq('input[name=labour_crews]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }

        if(current_ct == 17) {
            if (jq('input[name=hold_osha_certification]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }


        if(current_ct == 18) {
            if (!jq('input[class=ref_name]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Business referral name can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }

            if (!jq('input[class=ref_phone_number]').val()) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Business referral phone can not be blank!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                jq(form+' div.etf-community-module-loader').hide();
                return false;
            }
        }

        if(current_ct == 19) {
            if (jq('input[name=business_information]:checked').length <= 0) {
                jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            }
        }


        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        next_fs = jq(this).parent().next();

        //activate next step on progressbar using the index of next_fs
        jq("#progressbar li").eq(jq("fieldset").index(next_fs)).addClass("active");
        current_fs.hide();
        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute'
                });
                next_fs.css({'left': left, 'opacity': opacity});
            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct +1;
    });

    jq(".cprevious").click(function(){


        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        previous_fs = jq(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq(jq("fieldset").index(current_fs)).removeClass("active");
        current_fs.hide();
        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity, 'position': 'relative'});

            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct - 1;
    });

    jq("body").delegate('.js-contractor-delete', "click", function(e){
        e.preventDefault();
        if( confirm("Are you sure you want to delete this application?") ){
            var id = $(this).attr("data-req");
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "delete_contractor",
                    id: id
                },
                dataType: 'json',
                success: function (data) {
                    if(data.delete){
                        jq('.row-'+id).remove();
                    }

                    jq('div.response').html(data.msg);
                    jq("div.response").fadeIn(300,function() { setTimeout( 'jq("div.response").fadeOut(300)', 15000 ); });
                }
            });
        }
    });

    jq("body").delegate('.js-view-data', "click", function(e) {
        e.preventDefault();
        var id = jq(this).attr("data-req");
        var action = jq(this).attr("data-action")

        jq.ajax({
            method: "POST",
            url: etajaxurl,
            data: {
                fed_ajax_hook: action,
                id: id
            },
            dataType: 'json',
            success: function (data) {
                jq('.js-table-content').hide(100);
                jq('div.js-content-body').html(data.html);
                jq('div.js-content-body').show();
                loadGallery(true, 'a.thumbnail');

                jq(".js-contractors").chosen({allow_single_deselect: true});
            }
        });
    });

    jq("body").delegate('.js-close', "click", function(e) {
        e.preventDefault();
        jq('div.js-content-body').empty();
        jq('div.js-content-body').hide();
        jq('.js-table-content').show(500);
    });

    jq("body").delegate('.js-edit-data', "click", function(e) {
        e.preventDefault();
        var id = jq(this).attr("data-req");
        var action = jq(this).attr("data-action")

        jq.ajax({
            method: "POST",
            url: etajaxurl,
            data: {
                fed_ajax_hook: action,
                id: id
            },
            dataType: 'json',
            success: function (data) {
                jq('.js-table-content').hide(100);
                jq('div.js-content-body').html(data.html);
                jq('div.js-content-body').show();
                jq(".js-contractors").chosen({allow_single_deselect: true});
            }
        });
    });


    jq("body").delegate('.js-ad-delete', "click", function(e){
        e.preventDefault();
        if( confirm("Are you sure you want to delete this advertisement?") ){
            var id = $(this).attr("data-req");
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "delete_ad",
                    id: id
                },
                dataType: 'json',
                success: function (data) {
                    if(data.delete){
                        jq('.row-'+id).remove();
                    }
                }
            });
        }
    });

    jq("body").delegate('.js-ref-delete', "click", function (e) {
        e.preventDefault();
        var $this = jq(this);
        if (confirm("Are you sure you want to delete?")) {
            var id = $(this).attr("data-req");
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "delete_ref_by_id",
                    id: id
                },
                dataType: 'json',
                success: function (data) {
                    if (data.delete) {
                        $this.parent('li').parent('ul').remove();
                    }
                }
            });
        }
    });

    jq("body").delegate('.js-accept-req', "click", function (e) {
        e.preventDefault();
        var $this = jq(this);
        if (confirm("Are you sure you want to accept it?")) {
            var id = $(this).attr("data-req");
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "assign_rf_req_to_contract",
                    id: id
                },
                dataType: 'json',
                success: function (data) {
                    if (data.msg) {
                        $this.remove();
                        $this.append(data.msg);
                    }
                }
            });
        }
    });

    jq("body").delegate('.js-accept-offer', "click", function (e) {
        e.preventDefault();
        var $this = jq(this);
        if (confirm("Are you sure you want to accept this offer?")) {
            var id = jq(this).attr("data-comment");
            var con = jq(this).attr("data-con");
            var rq = jq(this).attr("data-rq");
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "offer_accept",
                    id: id,
                    con: con,
                    rq: rq
                },
                dataType: 'json',
                success: function (data) {
                    if (data.msg) {
                        $this.parent('p').append(data.msg);
                        $this.remove();
                    }
                }
            });
        }
    });


    jq(".js-url-state").chosen();
    jq(".js-url-state").change(function(){
        var state = jq(this).val();
        var url = jq(this).attr('data-url');
        window.location.href = url+'?state='+state;
        return true;
    });



    jq('#newModal').modal({backdrop: 'static', keyboard: false, show: false});
    jq('#newModal').on('hidden.bs.modal', function (event) {
        location.reload();
    });

    jq('#upModal').modal({backdrop: 'static', keyboard: false, show: false});
    jq('#upModal').on('hidden.bs.modal', function (event) {
        location.reload();
    });

    jq('#moresponsorModal').modal({backdrop: 'static', keyboard: false, show: false});
    jq('#moresponsorModal').on('hidden.bs.modal', function (event) {
        location.reload();
    });

    jq('#morepremierModal').modal({backdrop: 'static', keyboard: false, show: false});
    jq('#morepremierModal').on('hidden.bs.modal', function (event) {
        location.reload();
    });

    jq("body").delegate('.js-s-nxt', "click", function(e){
        e.preventDefault();
        jq(".js-show-nxt-button").show();
        jq(".js-ad-type").val(jq(this).attr('data-val'));
        jq(".js-ms-msg").show();
        if(jq(this).attr('data-val') == 'sponsor'){
            jq(".js-ms-msg").html("You have selected for <strong>Sponsor</strong>");
        } else {
            jq(".js-ms-msg").html("You have selected for <strong>Premier Sponsor</strong>");
        }
    });


    /*jq("body").delegate('.js-select-p', "click", function(e){
        e.preventDefault();
        jq(".js-package-submit").show();
        jq(".js-p-type").val(jq(this).val());
        jq('.js-select-p').attr('checked',false);
        jq(this).attr('checked',true);
        return true;
    });*/


    jq(".newnext").click(function(){


        if(current_ct == 1) {
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "get_ad_packeges",
                    type: jq('div#newModal .js-ad-type').val()
                },
                dataType: 'json',
                success: function (resp) {
                    jq('div#newModal div.js-all-packeges').empty();
                    jq('div.js-all-packeges').html(resp.data);
                }
            });
        }

        if(current_ct == 2) {
            if (jq('div#newModal input[name=package]:checked').length <= 0) {
                jq("div#newModal div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select a card package!</p>');
                jq("div#newModal div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div#newModal div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            } else {
                var p = jq("div#newModal input[name='package']:checked").val();
                jq("div#newModal .js-p-type").val(p);
                var base_url = window.location.origin;
                base_url = base_url+"/sponsor/payment/"+jq("div#newModal .js-ad-type").val()+"/"+jq("div#newModal .js-p-type").val();

                window.location.href = base_url;
            }
        }




        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        next_fs = jq(this).parent().next();

        //activate next step on progressbar using the index of next_fs
        jq("#progressbar li").eq(jq("fieldset").index(next_fs)).addClass("active");
        current_fs.hide();
        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute'
                });
                next_fs.css({'left': left, 'opacity': opacity});
            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct +1;
    });

    jq(".upwnext").click(function(){


        if(current_ct == 1) {
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "get_ad_packeges",
                    type: jq('div#upModal .js-ad-type').val()
                },
                dataType: 'json',
                success: function (resp) {
                    jq('div#upModal div.js-all-packeges').empty();
                    jq('div#upModal div.js-all-packeges').html(resp.data);
                }
            });
        }

        if(current_ct == 2) {
            if (jq('div#upModal input[name=package]:checked').length <= 0) {
                jq("div#upModal div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select a card package!</p>');
                jq("div#upModal div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div#newModal div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            } else {
                var p = jq("div#upModal input[name='package']:checked").val();
                jq("div#upModal .js-p-type").val(p);
                var base_url = window.location.origin;
                base_url = base_url+"/sponsor/payment/"+jq("div#upModal .js-ad-type").val()+"/"+jq("div#upModal .js-p-type").val();

                window.location.href = base_url;
            }
        }




        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        next_fs = jq(this).parent().next();

        //activate next step on progressbar using the index of next_fs
        jq("#progressbar li").eq(jq("fieldset").index(next_fs)).addClass("active");
        current_fs.hide();
        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute'
                });
                next_fs.css({'left': left, 'opacity': opacity});
            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct +1;
    });


    jq(".msnext").click(function(){


        if(current_ct == 1) {
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "get_ad_packeges",
                    type: jq('div#moresponsorModal .js-ad-type').val()
                },
                dataType: 'json',
                success: function (resp) {
                    jq('div#moresponsorModal div.js-all-packeges').empty();
                    jq('div#moresponsorModal div.js-all-packeges').html(resp.data);
                }
            });
        }

        if(current_ct == 2) {
            if (jq('div#moresponsorModal input[name=package]:checked').length <= 0) {
                jq("div#moresponsorModal div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select a card package!</p>');
                jq("div#moresponsorModal div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div#moresponsorModal div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            } else {
                var p = jq("div#moresponsorModal input[name='package']:checked").val();
                jq("div#moresponsorModal .js-p-type").val(p);
                var base_url = window.location.origin;
                base_url = base_url+"/sponsor/payment/"+jq("div#moresponsorModal .js-ad-type").val()+"/"+jq("div#moresponsorModal .js-p-type").val();

                window.location.href = base_url;
            }
        }




        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        next_fs = jq(this).parent().next();

        //activate next step on progressbar using the index of next_fs
        jq("#progressbar li").eq(jq("fieldset").index(next_fs)).addClass("active");
        current_fs.hide();
        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute'
                });
                next_fs.css({'left': left, 'opacity': opacity});
            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct +1;
    });

    jq(".mpnext").click(function(){


        if(current_ct == 1) {
            jq.ajax({
                method: "POST",
                url: etajaxurl,
                data: {
                    fed_ajax_hook: "get_ad_packeges",
                    type: jq('div#morepremierModal .js-ad-type').val()
                },
                dataType: 'json',
                success: function (resp) {
                    jq('div#morepremierModal div.js-all-packeges').empty();
                    jq('div#morepremierModal div.js-all-packeges').html(resp.data);
                }
            });
        }

        if(current_ct == 2) {
            if (jq('div#morepremierModal input[name=package]:checked').length <= 0) {
                jq("div#morepremierModal div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select a card package!</p>');
                jq("div#morepremierModal div.etf-community-ajax-feedback").fadeIn(300, function () {
                    setTimeout('jq("div#morepremierModal div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                });
                return false;
            } else {
                var p = jq("div#morepremierModal input[name='package']:checked").val();
                jq("div#morepremierModal .js-p-type").val(p);
                var base_url = window.location.origin;
                base_url = base_url+"/sponsor/payment/"+jq("div#morepremierModal .js-ad-type").val()+"/"+jq("div#morepremierModal .js-p-type").val();

                window.location.href = base_url;
            }
        }




        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        next_fs = jq(this).parent().next();

        //activate next step on progressbar using the index of next_fs
        jq("#progressbar li").eq(jq("fieldset").index(next_fs)).addClass("active");
        current_fs.hide();
        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute'
                });
                next_fs.css({'left': left, 'opacity': opacity});
            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct +1;
    });

    jq(".newprevious").click(function(){


        if(animating) return false;
        animating = true;

        current_fs = jq(this).parent();
        previous_fs = jq(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq(jq("fieldset").index(current_fs)).removeClass("active");
        current_fs.hide();
        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity, 'position': 'relative'});

            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        current_ct = current_ct - 1;
    });



    jq('input:radio[name=pack_info], input:radio[name=packages]').change(function () {
        var type = jQuery(this).attr('data-type');
        jq.ajax({
            method: "POST",
            url: etajaxurl,
            data: {
                fed_ajax_hook: "get_ad_location",
                type: type
            },
            dataType: 'json',
            success: function (resp) {
                jq('select.ad-location').empty();
                jq('select.ad-location').html(resp.data);
            }
        });
    });



    loadGallery(true, 'a.thumbnail');

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current){
        jq('#show-previous-image, #show-next-image').show();
        if(counter_max == counter_current){
            jq('#show-next-image').hide();
        } else if (counter_current == 1){
            jq('#show-previous-image').hide();
        }
    }

    /**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr){
        var current_image,
            selector,
            counter = 0;

        jq("body").delegate('#show-next-image, #show-previous-image', "click", function(e){
            e.preventDefault();
        //jq('#show-next-image, #show-previous-image').click(function(){
            if(jq(this).attr('id') == 'show-previous-image'){
                current_image--;
            } else {
                current_image++;
            }

            selector = jq('[data-image-id="' + current_image + '"]');
            updateGallery(selector);
        });

        function updateGallery(selector) {
            var $sel = selector;
            current_image = $sel.data('image-id');
            jq('#image-gallery-caption').text($sel.data('caption'));
            jq('#image-gallery-title').text($sel.data('title'));
            jq('#image-gallery-image').attr('src', $sel.data('image'));
            disableButtons(counter, $sel.data('image-id'));
        }

        if(setIDs == true){
            jq('[data-image-id]').each(function(){
                counter++;
                jq(this).attr('data-image-id',counter);
            });
        }
        jq(setClickAttr).on('click',function(){
            updateGallery(jq(this));
        });
    }
});

/**
 *
 * @returns {Number}
 */
function getLastUpdatedPrivacyTime() {
    return (1473984000);
}

/**
 *
 */
function getQSParameterByName(name, href) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(href);
    if (results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

function etf_hub_form(obj){
    jq(obj).ajaxSubmit({
        beforeSubmit: function(d,f,o) {
            o.dataType = 'html';
            var form_class = f.attr("class");
            var form_val_class = f.attr("data-cl");
            var form = (form_class?"form[class='"+form_class+"']":"");
            jq(form+' div.etf-community-ajax-feedback').empty();
            jq(form+' div.etf-community-ajax-feedback').hide();
            jq(form+" button[name='etf-community-post-submit']").attr('disabled', 'disabled');
            jq(form+' div.etf-community-module-loader').show();

            if(form_val_class == "roofing") {

                if (jq('input[name=best_time_contact]:checked').length <= 0) {
                    jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                    jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                        setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                    });
                    jq(form+' div.etf-community-module-loader').hide();
                    return false;
                }

            }

            if(form_val_class == "contractor") {
                if (jq('input[name=interested_in_advertising]:checked').length <= 0) {
                    jq("div.etf-community-ajax-feedback").html('<p class="box alert text-left">Select required fields!</p>');
                    jq("div.etf-community-ajax-feedback").fadeIn(300, function () {
                        setTimeout('jq("div.etf-community-ajax-feedback").fadeOut(300)', 5000);
                    });
                    jq(form+' div.etf-community-module-loader').hide();
                    return false;
                }
            }
        },
        success: function(data,s,x,f) {
            data = jq.trim(data);
            var error = false;
            var show_message = true;
            var form_class = f.attr("class");
            var form = (form_class?"form[class='"+form_class+"']":"");

            var jqout = jq(form+' div.etf-community-ajax-feedback');
            if (typeof data == 'object' && data.nodeType)
                data = elementToString(data.documentElement, true);
            else if (typeof data == 'object')
                data = objToString(data);

            if( jq("input[name='redirect']").val() && ( data.charAt(0) + data.charAt(1) != '-1' ) ){
                window.location.href = jq("input[name='redirect']").val();
                return true;
            }
            if ( data.charAt(0) + data.charAt(1) == '-1' ) {
                console.log(data.substr( 2, data.length ));
                jq(form+" div.etf-community-ajax-feedback").html(data.substr( 2, data.length ));
                jq(form+" div.etf-community-ajax-feedback").fadeIn(300,function() { setTimeout( 'jq("'+form+' div.etf-community-ajax-feedback").fadeOut(300);', 15000 ); });
                error = true;
            }

            if( !error ){
                if( !jq("input[name='noreset']").val() ){
                    jq(form).clearForm();
                    jq(form).resetForm();
                }


                jqout.html(data);
                jqout.fadeIn(300,function() { setTimeout( 'jq("div.etf-community-ajax-feedback").fadeOut(300);', 120000 ); });
                //jq('body').scrollTo('div.etf-community-ajax-feedback', 500, {offset:-150} );

            }
            jq('div.etf-community-module-loader').hide();
            jq("button[name='etf-community-post-submit']").removeAttr('disabled');
        }
    });
};

/**
 *
 * @param {type} $pass1
 * @param {type} $pass2
 * @param {type} $strengthResult
 * @param {type} blacklistArray
 * @returns {unresolved}
 */
function checkPasswordStrength($pass1, $pass2, $strengthResult, blacklistArray) {
    var pass1 = $pass1.val();
    var pass2 = $pass2.val();

    // Reset the form & meter
    //$submitButton.attr( 'disabled', 'disabled' );
    $strengthResult.removeClass('short bad good strong');

    // Extend our blacklist array with those from the inputs & site data
    blacklistArray = blacklistArray.concat(wp.passwordStrength.userInputBlacklist())

    // Get the password strength
    var strength = wp.passwordStrength.meter(pass1, blacklistArray, pass2);

    // Add the strength meter results
    switch (strength) {
        case 2:
            $strengthResult.addClass('bad').html('Very Weak');
            break;
        case 3:
            $strengthResult.addClass('good').html('Medium');
            break;
        case 4:
            $strengthResult.addClass('strong').html('Strong');
            break;
        case 5:
            $strengthResult.addClass('short').html('Mismatch');
            break;
        default:
            $strengthResult.addClass('short').html('Too Short');

    }

    // The meter function returns a result even if pass2 is empty,
    // enable only the submit button if the password is strong and
    // both passwords are filled up
    if (4 === strength && '' !== pass2.trim()) {
        //$submitButton.removeAttr( 'disabled' );
    }

    return strength;
}

/**
 *
 * @param {type} name
 * @param {type} current_url
 * @returns {unresolved}
 */
function getParameterByName(name, current_url) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(current_url) || [, ""])[1].replace(/\+/g, '%20')) || null;
}

/**
 * Email validation
 *
 * @param email
 * @returns {boolean}
 */
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
