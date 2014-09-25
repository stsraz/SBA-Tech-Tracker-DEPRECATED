/**
 * @author Joseph Rasmussen
 */

jQuery(document).ready(function() {
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
				jQuery("#tracker_bb1").buttonset();
				jQuery("#tracker_bb2").buttonset();
			});
			//jQuery(".my_buttonbar").hide();
			jQuery('#tracker_two3').countdown({since: 0, format: "HMS", compact: true});
			jQuery('#tracker_two4').countdown({until: '+15m', format:"MS", compact: true});
			jQuery('#tracker_two5').countdown({since: 0, format:"HM", compact: true});
			jQuery('#activation_information').accordion();
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
	// A widget to populate divs with jQuery timers
	jQuery.widget("sba.insert_timer", {
		// Default options
		options: {
			until: null,
			since: null
		},
		
		_create: function() {
			
		}
	});
});
