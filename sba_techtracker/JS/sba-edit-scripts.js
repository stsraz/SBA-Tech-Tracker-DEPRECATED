jQuery(document).ready( function() {
	var data = {
		action: 'edit_callback',
		security: MyAjax.security,
		callback_type: 0
	};
	jQuery.post(MyAjax.ajaxurl, data, function(response){
		document.getElementById('edit_content').innerHTML = response;
	});
});

jQuery(document).on("click", "#single_activation_button", function() {
	var data = {
		action: 'edit_callback',
		security: MyAjax.security,
		callback_type: 1
	};
	jQuery.post(MyAjax.ajaxurl, data, function(response){
		document.getElementById('edit_content').innerHTML = response;
	});
});

jQuery(document).on("click", "#multi_activation_button", function() {
	alert("Coming soon!");
});

jQuery(document).on("change", "#single_activation_select", function() {
	var selected_single_store = jQuery('#single_activation_select').val();
	var data = {
		action: 'edit_callback',
		security: MyAjax.security,
		selected_single_store: selected_single_store,
		callback_type: 2
	};
	jQuery.post(MyAjax.ajaxurl, data, function(response){
		document.getElementById('edit_content').innerHTML = response;
	});
});

jQuery(document).on("click", "#submit_single_changes", function() {
	var eon_number = jQuery('#selected_eon_number').val();
	var ops_console = jQuery('#selected_ops_console_number').val();
	var bridge_access = jQuery('#selected_bridge_access_code').val();
	var sa_tech = jQuery('#selected_assigned_sa_tech').val();
	var selected_single_store = jQuery('#single_activation_select').val();
	var verify = confirm('Are you sure you want to save this data to the database?');
	if(verify == true) {
		var data = {
			action: 'edit_callback',
			security: MyAjax.security,
			new_eon: eon_number,
			new_ops: ops_console,
			new_bridge: bridge_access,
			new_tech: sa_tech,
			selected_single_store: selected_single_store,
			callback_type: 3
		};
		jQuery.post(MyAjax.ajaxurl, data, function(response) {alert('Save Successful');});
		var data = {
			action: 'edit_callback',
			security: MyAjax.security,
			selected_single_store: selected_single_store,
			callback_type: 2
		};
		jQuery.post(MyAjax.ajaxurl, data, function(response) {
			document.getElementById('edit_content').innerHTML = response;
		});
	}
});
