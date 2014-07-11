jQuery(document).ready(function() {
	get_activations();
	start_timer();
});

function start_timer() {
	var get_act = setInterval(function(){get_activations()}, 60000);
}

function get_activations() {
    var data = {
        action: 'status_callback',
        security: MyAjax.security,
        callback_type: 0
    };
    jQuery.post(MyAjax.ajaxurl, data, function(response) {
        document.getElementById('summary_content').innerHTML = response;
        var in_progress_table_size = document.getElementById('in_progress_table').rows.length;
        var on_deck_table_size = document.getElementById('on_deck_table').rows.length;
        var unassigned_table_size = document.getElementById('unassigned_table').rows.length;
        
        for( i = 1; i<in_progress_table_size; i++ ) {
            var total_div = 'total_div_' + i;
            var step_div = 'step_div_' + i;
            var stepChanged = document.getElementById('step_div_' + i).getAttribute("data-step");
            var startTime = document.getElementById('total_div_' + i).getAttribute("data-total");

            jQuery( '#total_div_' + i ).countdown({
                    since: -startTime, 
                    format: 'HMS', 
                    padZeroes: true, 
                    compact: true
            });
            jQuery( '#step_div_' + i ).countdown({
                    since: -stepChanged,
                    format: 'HMS',
                    padZeroes: true,
                    compact: true
            });
        }
        
        for( i = 1; i<on_deck_table_size; i++ ) {
            var until_div = 'until_start_div_' + i;
            var until_start = document.getElementById(until_div).getAttribute("data-until");//'+15m';
			var d = new Date();
			var current_time = d.getTime();
			current_time = to_seconds(current_time);
			var time_until_start = until_start - current_time;
            
            jQuery('#' + until_div).countdown({
                until: time_until_start,
                format: 'HMS',
                padZeroes: true,
                compact: true
            });
        }
        
        for( i = 1; i<unassigned_table_size; i++) {
            var until_unassigned_div = 'until_unassigned_div_' + i;
            var until_unassigned = document.getElementById(until_unassigned_div).getAttribute("data-until");//'+15m';
            var d = new Date();
			var current_time = d.getTime();
			current_time = to_seconds(current_time);
			var time_to_start = until_unassigned - current_time;
			
            jQuery('#' + until_unassigned_div).countdown({
                until: time_to_start,
                format: 'HMS',
                padZeroes: true,
                compact: true
            });
        }
    });
}


function to_seconds(milliseconds) {
	seconds = milliseconds / 1000;
	return seconds;
}