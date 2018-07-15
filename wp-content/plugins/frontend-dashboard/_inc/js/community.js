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
    jq('.date_time_picker').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'HH:mm:ss'
    });

    jq("body").delegate("form#etf-hub-form input[name='etf-hub-form-submit'], form#etf-hub-form button[name='etf-hub-form-submit']", "click", function (e) {
        e.preventDefault();
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



    jq("form[name='etf-community-form'] button[name='etf-hub-form-submit'], form[name='etf-community-form'] input[name='etf-hub-form-submit']").click(function(e){
        e.preventDefault();
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

    jq(".js-chosen").chosen();
    jq(".js-url-state").chosen();
    jq(".js-url-state").change(function(){
        var state = jq(this).val();
        var url = jq(this).attr('data-url');
        window.location.href = url+'?state='+state;
        return true;
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

    var email_validate = false;


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



    //For tables

    jq('#agent_table').DataTable({
        "bProcessing": true,
        "serverSide": true,
        "pageLength": 50,
        "ajax":{
            url :etajaxurl, // json datasource
            type: "post",  // type of method  ,GET/POST/DELETE
            data: {
                "fed_ajax_hook": "get_all_agents"
            },
            error: function(){
                jq("#agent_table_processing").css("display","none");
            }
        },
        "columns": [
            {
                "data": "row_id"
            },
            {
                "data": "user_login"
            },
            {
                "data": "pwd"
            },
            {
                "data": "first_name"
            },
            {
                "data": "dist_id"
            },
            {
                "data": "target_lead"
            },
            {
                "data": "reg_lead"
            },
            {
                "data": "target_start"
            },
            {
                "data": "target_end"
            },
            {
                "data": "user_status"
            },
            {
                "data": "actions"
            }

        ],
        "order": [[0, 'asc']],
        "aoColumnDefs" : [
            {
                'bSortable' : false,
                'aTargets' : [ 0, 2,3,4,5,6,7,8,10 ]
            }],

        "initComplete": function () {
            this.api().columns().every(function () {
                var column = this;

                if (column.index() == 10) {
                    var select = jq('<select><option value="" selected>Select Status</option></select>')
                        .appendTo(jq("#filters").find("th").eq(column.index()))
                        .on('change', function () {
                            var val = jq.fn.dataTable.util.escapeRegex($(this).val());

                            column.search(val, true, false).draw();
                        });

                    select.append('<option value="1">Active</option>');
                    select.append('<option value="2">Deactive</option>');
                }

                jQuery('[data-toggle="popover"]').popover();
            });

        }


    });


    //User Delete
    jq("body").delegate(".js-request-delete", "click", function (e) {
        e.preventDefault();
        if( confirm("Are you sure you want to delete this user?") ) {
            var id = jq(this).attr('data-req');
            var redirect = jq(this).attr('data-redirect');
            var url = jq(this).attr('data-url');
            var $obj = jq(this);
            jq.post(etajaxurl, {
                id: id,
                fed_ajax_hook: "delete_user"
            }, function (data) {
                if (data.delete) {
                    if(redirect == "yes"){
                        alert("User has been deleted successfully");
                        window.location.href = url;
                        return true;
                    } else {
                        jq("div.response").html(data.msg);
                        jq("div.response").fadeIn(300, function () {
                            setTimeout('jq("div.response").fadeOut(300)', 5000);
                        });
                        $obj.parent('td').parent('tr').hide();
                    }
                } else {
                    jq("div.response").html(data.msg);
                    jq("div.response").fadeIn(300, function () {
                        setTimeout('jq("div.response").fadeOut(300)', 5000);
                    });
                }
            }, "json");
        }
    });

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
