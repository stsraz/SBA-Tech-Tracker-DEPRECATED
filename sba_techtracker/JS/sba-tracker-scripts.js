/**
*  SBA Tech Tracker Javascript file.
*/

var activation_step = 0;
var selected_store;
var stime = 0;
var help_controller = 1;
var steps_array = new Array();
var times_array = new Array();
for(i = 0; i<15; i++) {
    times_array[i] = 0;
}

jQuery(document).ready(function() {
        //check_cookie();
	
	//Display the choose store page
	var data = {
		action: 'callback_function',
		security: MyAjax.security,
		callback_type: 0
	};

	jQuery.post(MyAjax.ajaxurl, data, function(response){
		document.getElementById('tracker_content').innerHTML = response;
		jQuery('#start_activation').hide();
	});

	jQuery(document).on('change', '#pending_select', function(){
		var selected_store = jQuery('#pending_select').val();
		var data = {
			action: 'callback_function',
			security: MyAjax.security,
			store: selected_store,
			callback_type: 1
		};
	
		jQuery.post(MyAjax.ajaxurl, data, function(response){
			document.getElementById('preview_table_div').innerHTML = response;
			jQuery('#start_activation').show();
		});
	});

	jQuery(document).on('click', '#start_activation', function() {
		selected_store = jQuery('#pending_select').val();
		activation_step = activation_step + 1;
                //Creates a current time variable to post on starting the activation.
                var d = new Date();
                current_time = to_seconds(d.getTime());
                times_array['start'] = current_time;
                
                selected_store = jQuery('#pending_select').val();
                
                var data = {
                        action: 'callback_function',
                        security: MyAjax.security,
                        store: selected_store,
                        time: current_time,
                        callback_type: 2
                };

                jQuery.post(MyAjax.ajaxurl, data, function(response){
                        document.getElementById('tracker_content').innerHTML = response;
                        //set_cookie("store_number", selected_store, "activation_step", activation_step, 12);
                        create_buttons();
                        get_steps();
                        start_timers();
                        send_times(1);
                });
	});
        
        jQuery(document).on('click', '#next_button', function() {
            send_times(1);
            activation_step = activation_step + 1;
            stime = times_array[activation_step];
            jQuery('#step_timer').countdown('option', 'since', -stime);
            jQuery('#escalate_timer').countdown('option', 'until', '+15m');
            document.getElementById('step_div').innerHTML = steps_array[activation_step - 1]['step_data'];
            if(activation_step === 13) {
                jQuery('#next_button').hide();
                jQuery('#finish_button').show();
            };
        });
        
        jQuery(document).on('click', '#back_button', function() {
                if(activation_step === 1){}
                else{
                    send_times(0);
                    activation_step = activation_step - 1;
                    stime = times_array[activation_step];
                    jQuery('#step_timer').countdown('option', 'since', -stime);
                    jQuery('#escalate_timer').countdown('option', 'until', '+15m');
                    document.getElementById('step_div').innerHTML = steps_array[activation_step - 1]['step_data'];
                    if(activation_step === 12) {
                        jQuery('#next_button').show();
                        jQuery('#finish_button').hide();
                    };
                };
        });
        
        jQuery(document).on('click', '#pause_button', function() {
	    var pause = jQuery(this).text() === 'Pause'; 
	    jQuery(this).text(pause ? 'Resume' : 'Pause'); 
	    jQuery('#step_timer').countdown(pause ? 'pause' : 'resume');
	    jQuery('#escalate_timer').countdown(pause ? 'pause' : 'resume');
	});
        
        jQuery(document).on('click', '#finish_button', function() {
            finish();
	});
        
        jQuery(document).on('click', '#abort_button', function() {
            var abort = confirm("Are you sure you want to end this activation?");
            if(abort) {
                finish();
            }
        });
        
        jQuery(document).on('click', '#help_button', function() {
            help_controller = help_controller/-1;
            var help = jQuery(this).text() === 'Help';
            jQuery(this).text(help ? 'Cancel' : 'Help');
            jQuery('#help_timer_div').countdown(help ? {since: 0, format: 'HMS', padZeroes: true, description: '<br><b>Waiting for Help</b>', compact: true} : 'destroy');
            if(help_controller == -1){
	            var help_reason = prompt("Please explain why help is needed: ", "Enter reason here");
	            need_help(help_controller, help_reason);
    	        alert("A lead will be along shortly to help with: " + help_reason);
    	    }
    	    else{
    	    	help_reason = "None";
    	    	need_help(help_controller, help_reason);
    	    }
        });
});

function finish() {
    var d = new Date();
    var current_time = to_seconds(d.getTime());
    send_times(1);
    //times_array['end'] = current_time;
    json_times = JSON.stringify(times_array);
    var data = {
        action: 'callback_function',
        security: MyAjax.security,
        store: selected_store,
        end: current_time,
        json_times: json_times,
        callback_type: 5
    };

    jQuery.post(MyAjax.ajaxurl, data, function(response){
        //location.reload(true);
        window.location = 'http://127.0.0.1:4567/?page_id=14';
    });
}

function need_help(help_controller, help_reason) {
    if(help_controller === -1) {
        var d = new Date();
        var current_time = to_seconds(d.getTime());
        var data = {
            action: 'callback_function',
            security: MyAjax.security,
            store: selected_store,
            help: 1,
            help_time: current_time,
            help_reason: help_reason,
            callback_type: 7
        };
        jQuery.post(MyAjax.ajaxurl, data, function(response){});
    };
        if(help_controller === 1) {
        var d = new Date();
        var current_time = to_seconds(d.getTime());
        var data = {
            action: 'callback_function',
            security: MyAjax.security,
            store: selected_store,
            help: 0,
            help_time: 0,
            help_reason: help_reason,
            callback_type: 7
        };
        jQuery.post(MyAjax.ajaxurl, data, function(response){});
    };

}

function get_steps() {
    var data = {
        action: 'callback_function',
        security: MyAjax.security,
        store: selected_store,
        callback_type: 6
    };
    jQuery.post(MyAjax.ajaxurl, data, function(response){
        steps_array = response;
        
        document.getElementById('step_div').innerHTML = steps_array[0]['step_data'];
    });
}

function send_times(move_type) {
    if(move_type === 1) {
        step = activation_step + 1;
    }
    else if(move_type === 0) {
        step = activation_step - 1;
    }
    //Get the total step time
    var step_periods = jQuery('#step_timer').countdown('getTimes');
    var step_time = jQuery.countdown.periodsToSeconds(step_periods);
    times_array[activation_step] = step_time;
    
    var d = new Date();
    current_time = to_seconds(d.getTime());
    
    var data = {
        action: 'callback_function',
        security: MyAjax.security,
        step: step,
        store: selected_store,
        time: current_time,
        callback_type: 3
    };
    jQuery.post(MyAjax.ajaxurl, data, function(response){});
}

function to_seconds($milliseconds) {
    var seconds = $milliseconds / 1000;
    seconds = Math.round(seconds);
    return seconds;
}

function create_buttons() {
    var buttonNames = ['Pause', 'Resume', 'Back', 'Help', 'Next', 'End Activation', 'Finish'];
    var buttonIds = ['pause', 'resume', 'back', 'help', 'next', 'abort', 'finish'];

    for (var i = 0; i < 7; i++) {
        var button = document.createElement("BUTTON");
        var buttonText = document.createTextNode(buttonNames[i]);
        button.appendChild(buttonText);
        button.id = buttonIds[i] + '_button';
        document.getElementById(buttonIds[i] + '_div').appendChild(button);
    }

    jQuery('#resume_button').hide();
    jQuery('#finish_button').hide();
}

function start_timers() {
    jQuery( '#total_timer' ).countdown({
            since: 0, 
            format: 'HMS', 
            padZeroes: true, 
            description: '<br><b>Total Activation Time</b>',
            compact: true
    });
    jQuery( '#step_timer' ).countdown({
            since: 0,
            format: 'HMS',
            padZeroes: true,
            description: '<br><b>Time on Current Step</b>',
            compact: true
    });
    jQuery( '#escalate_timer' ).countdown({
            until: '15m',
            format: 'MS',
            padZeroes: true,
            description: '<br><b>Time to Escalation</b>',
            compact: true
    });
}