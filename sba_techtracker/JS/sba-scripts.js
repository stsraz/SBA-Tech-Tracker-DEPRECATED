/**
 * @author Joseph Rasmussen
 */

jQuery(document).ready(function() {
	post_call("populate_my_dom");
	post_call("get_static_data");
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
			break;
		case 'get_static_data':
			var num_divs=0;
			var temp_handle=new Array;
			var temp_data=new Array;
			
			var json=JSON.parse(response,function(name,value) {
				if(name=='handle') {
					num_divs++;
				}
				return value;
			});
			
			for(i=0;i<num_divs;i++) {
				temp_handle[i]=json[i].handle[0];
				temp_data[i]=json[i].data[0];
			}
			for(i=0;i<num_divs;i++) {
				jQuery(temp_handle[i]).append_element({response:temp_data[i]});
			}
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
});
