/**
 * @author Joseph Rasmussen
 */

jQuery(document).ready(function() {
	var data = {
		action: 'sba_callback',
		security: MyAjax.security,
		type: 'populate'
	};
	jQuery.post(MyAjax.ajaxurl,data,function(response) {
		jQuery("#content").html(response);
		jQuery("#tabs").tabs();
		
		jQuery("#start_0").html("<p>Start Page Content</p>");
		jQuery("#tracker_0").html("<p>Tracker Content</p>");
		jQuery("#precheck_0").html("<p>Precheck Content</p>");
		jQuery("#view_0").html("<p>View Activation Content</p>");
		jQuery("#summary_0").html("<p>Activation Summary Content</p>");
		
		jQuery("#start_15").html("<p>Start Page Content</p>");
		jQuery("#tracker_15").html("<p>Tracker Content</p>");
		jQuery("#precheck_15").html("<p>Precheck Content</p>");
		jQuery("#view_15").html("<p>View Activation Content</p>");
		jQuery("#summary_15").html("<p>Activation Summary Content</p>");

	});
});

