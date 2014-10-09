/**
 * @author Joseph Rasmussen
 */

jQuery(document).ready(function() {
	jQuery(function(){
		// Keep the page alive so session variables don't time out
		//setInterval(function(){post_call('keep_alive');}, 30000);
	});
	post_call("populate_my_dom");
});

function post_call(type) {
	var data = {
		action: 'sba_callback',
		security: MyAjax.security,
		type: type
	};
	jQuery.post(MyAjax.ajaxurl,data,function(response) {
		do_action(type,response);
	});
}

function do_action(type,response) {
	switch(type) {
		case 'keep_alive':
			
			break;
		case 'populate_my_dom':
			jQuery("#my_content").append_element({response:response});
			jQuery("#tabs").tabs();
			jQuery(function() {
				jQuery("#back").button({
					text: true
				});
				jQuery("#pause").button({
					text: true
				});
				jQuery("#next").button({
					text: true
				});
				jQuery("#help").button({
					text: true
				});
				jQuery("#abort").button({
					text: true
				});
				jQuery("#start_button").button({
					text:true
				});
				jQuery("#tracker_bb1").buttonset();
				jQuery("#tracker_bb2").buttonset();
				jQuery("#start_bar").buttonset();
			});
			jQuery("#tracker_zero_table,#tracker_one_table,#tracker_two_table").hide();
			jQuery('#activation_information').accordion({heightStyle:"content"});
			jQuery("#start_button").on('click',function() {
				do_action('start_activation',0);
			});
			jQuery("#store_select").on('change',function() {
				var requested=jQuery("#store_select").val();
				var data = {
					action: 'sba_callback',
					security: MyAjax.security,
					type: 'show_requested_store',
					requested: requested
				};
				jQuery.post(MyAjax.ajaxurl,data,function(response) {
					var temp_response=response;
					temp_response=JSON.parse(temp_response);
					for(var i=0;i<temp_response.length;i++) {
						temp_div="#"+temp_response[i].div;
						temp_data=temp_response[i].data;
						jQuery(temp_div).html(temp_data);
					}
				});
			});
			break;
		case 'start_activation':
			jQuery('#start_bar,#tracker_four0').hide();
			jQuery("#tracker_zero_table,#tracker_one_table,#tracker_two_table").show();
			jQuery('#tracker_two3').countdown({since: 0, format: "HMS", compact: true});
			jQuery('#tracker_two4').countdown({until: '+15m', format:"MS", compact: true});
			jQuery('#tracker_two5').countdown({since: 0, format:"HM", compact: true});
			break;
	}
}

jQuery(function() {
	// A widget to populate an element
	jQuery.widget("sba.append_element", {
		// Default options
		options: {
			response: null
		},
		
		_create: function() {
			this.element
				.append(this.options.response);
		}
	});
	// A widget to replace the contents of an element
	jQuery.widget("sba.replace_element", {
		// Default options
		options: {
			response: null
		},
		
		_create: function() {
			this.element
				.replaceAll(this.options.response);
		}
	});
});
