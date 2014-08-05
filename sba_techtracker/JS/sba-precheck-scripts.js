/**
 * @author Joseph Rasmussen
 */

jQuery(document).ready(function() {
	jQuery("#tabs").tabs({
		activate: function(event,ui) {
			var text = ui.newTab.text();
			show_tab(text);
		}
	});
});

function show_tab(index) {
	var data = {
		action: 'precheck_callback',
		security: MyAjax.security,
		type: 'show_precheck_'
	};
	jQuery.post(MyAjax.ajaxurl,data,function(response) {
		jQuery("#precheck").html(response);
	});
}
