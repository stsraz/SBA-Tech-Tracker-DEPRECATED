jQuery(document).ready( function() {
    var data = {
        action: 'finish_callback',
        security: MyAjax.security,
        callback_type: 0
    };
    
    jQuery.post(MyAjax.ajaxurl, data, function(response) {
        document.getElementById('finish_content').innerHTML = response;
    });
    
    jQuery(document).on('click', '#send_rating', function() {
    	tech_rating = jQuery("#rating input[type = 'radio' ]:checked").val();
    	comments = jQuery('#comments').val();
    	    	
    	var data = {
    		action: 'finish_callback',
    		security: MyAjax.security,
    		tech_rating: tech_rating,
    		comments: comments,
    		callback_type: 1
    	};
    	
    	jQuery.post(MyAjax.ajaxurl, data, function(response) {
    		alert('Thanks for your input!');
    		//window.location = 'http://10.1.43.254';
    		//For Testing
    		window.location = 'http://localhost:4567';
    	});
    });
});
