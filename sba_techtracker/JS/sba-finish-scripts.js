jQuery(document).ready( function() {
    var data = {
        action: 'abort_callback',
        security: MyAjax.security,
        callback_type: 0
    }
    
    jQuery.post(MyAjax.ajaxurl, data, function(response) {
        document.getElementById('abort_content').innerHTML = response;
    });
});