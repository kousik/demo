var jq = jQuery;

jq(document).ready( function() {
	jq("body").find("form#etf-community-form").each(function(e){				
		jq(this).delegate("button[name='etf-community-post-submit']", "click", function(e){
			// submit the form 
			var form = jq(this);
			do{
				form = form.parent();
			}
			while( !form.is("form") );
			etf_community_form(form);
			// return false to prevent normal browser submit and page navigation 
			return false; 
		});
	});
});

function etf_community_form(obj){
	jq(obj).ajaxSubmit({
		beforeSubmit: function(d,f,o) {
			o.dataType = 'html';
			var form_class = f.attr("class");
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
				
			if( jq(form+" input[name='redirect']").val() && ( data.charAt(0) + data.charAt(1) != '-1' ) ){
				window.location.href = jq(form+" input[name='redirect']").val();
				return true;
			}
			if ( data.charAt(0) + data.charAt(1) == '-1' ) {
                jq(form+" > div.etf-community-ajax-feedback").html(data.substr( 2, data.length ));
                jq(form+" > div.etf-community-ajax-feedback").fadeIn(300,function() { setTimeout( 'jq("'+form+' > div.etf-community-ajax-feedback").fadeOut(300)', 15000 ); });
				error = true;
			}
			
			if( !error ){
				if( !jq(form+" input[name='noreset']").val() ){
					jq(form).clearForm();
					jq(form).resetForm();
				}
				
				if( form_class == 'topic-replies' ){
					jq("a[id^='etf-hub-profiling-action-link-']:first").hide();			
					jq("div#etf-community-topic-container").append(data);
					jq("div#etf-community-topic-container div:last").addClass('new-update');
					jq("div.new-update").hide().slideDown( 300 );
					jq("div.new-update").removeClass( 'new-update' );
					jq('body').scrollTo('div#etf-community-topic-container div:last', 500, {offset:-150} );
				}
				else if( form_class == 'activity' ){
					jq('file[name="photo"]').clearFields();			
					if ( 0 == jq("div#activity-container div:first").length ) {
						jq("div#activity-container").empty();
					}
					jq("div#activity-container").prepend(data);
					jq("div#activity-container div:first").addClass('new-update');
					jq("div.new-update").hide().slideDown( 300 );
					setTimeout( "jq('div.new-update').removeClass( 'new-update' )", 10000 );
					jq('body').scrollTo('div#activity-container div:first', 500, {offset:-150} );
				}			
				else{
                    jqout.html(data);
 					jqout.fadeIn(300,function() { setTimeout( 'jq("'+form+' > div.etf-community-ajax-feedback").fadeOut(300)', 15000 ); });
					jq('body').scrollTo(form+' > div.etf-community-ajax-feedback', 500, {offset:-150} );
				}
			}
           	jq(form+' div.etf-community-module-loader').hide();
			jq(form+" button[name='etf-community-post-submit']").removeAttr('disabled');			
		}
	});
};
