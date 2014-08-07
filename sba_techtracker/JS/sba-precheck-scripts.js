/**
 * @author Joseph Rasmussen
 */

jQuery(document).ready(function() {
	var data = {
		action: 'precheck_callback',
		security: MyAjax.security,
		type: 'populate',
		tab: 'start'
	};
	jQuery.post(MyAjax.ajaxurl,data,function(response) {
		jQuery("#content").html(response);
		jQuery("#tabs").tabs();		
		jQuery("#start_0").html("<p>Test,Test</p>");
	});
});

