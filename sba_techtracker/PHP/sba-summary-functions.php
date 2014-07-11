<?php

session_start();

function sba_status_callback() {
	check_ajax_referer( 'sba-security-string', 'security');
	$callback_type = $_POST['callback_type'];
	switch($callback_type) {
            case 0:
                set_the_table();
                die();
                break;
	}
}
add_action( 'wp_ajax_status_callback', 'sba_status_callback' );

function set_the_table() {
    get_counters();
    if($_SESSION['in_progress'] == 1) {
        $in_progress_display = "1 Activation In Progress";
    } else {
        $in_progress_display = $_SESSION['in_progress'] . " Activations In Progress";
    }
    if($_SESSION['on_deck'] == 1) {
        $on_deck_display = "1 Upcoming Activation";
    } else {
        $on_deck_display = $_SESSION['on_deck'] . " Upcoming Activations";
    }
    if($_SESSION['unassigned'] == 1) {
        $unassigned_display = "1 Unassigned Activation";
    } else {
        $unassigned_display = $_SESSION['unassigned'] . " Unassigned Activations";
    }
    echo <<<StringOne
            <div id = 'in_progress_div'>$in_progress_display</div>
            <table id = 'in_progress_table' class = 'status_table'>
                    <thead>
                        <tr>
                            <td>SA Tech</td>
                            <td>Store Number</td>
                            <td>Activation Type</td>
                            <td>Current Step</td>
                            <td>Time on Current Step</td>
                            <td>Total Activation Time Elapsed</td>
                        </tr>
                    </thead>
                    <tbody>
StringOne;
    get_current_activations();
    echo <<<StringTwo
                    </tbody>
            </table>
            <div id = 'on_deck_div'>$on_deck_display</div>
            <table id = 'on_deck_table' class = 'status_table'>
                <thead>
                    <tr>
                        <td>SA Tech</td>
                        <td>Store Number</td>
                        <td>Activation Type</td>
                        <td>Time to Activation</td>
                    </tr>
                </thead>
                <tbody>
StringTwo;
    get_on_deck();
    echo <<<StringThree
            </tbody></table>
            <div id = 'unassigned_div'>$unassigned_display</div>
            <table id = 'unassigned_table' class = 'status_table'>
                <thead>
                    <tr>
                        <td>Store Number</td>
                        <td>Activation Type</td>
                        <td>Time to Activation</td>
                    </tr>
                </thead>
                <tbody>
StringThree;
    get_unassigned();
    echo <<<StringFour
            </tbody></table>
StringFour;
}

function get_counters() {
    $dbconn = connect_db();
    //Count in progress activations
    $sql = <<<SQL
            SELECT COUNT(store_number) AS "In Progress"
            FROM
                activation
            WHERE
                activation_in_progress = 1
SQL;
    $result = $dbconn->query($sql);
    foreach($result as $counter) {
        $_SESSION['in_progress'] = $counter["In Progress"];
    }
    //Count on deck activations
    $sql = <<<SQL
            SELECT COUNT(store_number) AS "On Deck"
            FROM
                activation,precheck
            WHERE
				activation.store_number = precheck.store_number_precheck
			AND
                activation_is_tonight = 1
            AND
                activation_in_progress = 0
			AND
				assigned_sa_tech != "Unassigned"
SQL;
    $result = $dbconn->query($sql);
    foreach($result as $counter) {
        $_SESSION['on_deck'] = $counter["On Deck"];
    }
    //Count unassigned activations
    $sql = <<<SQL
            SELECT COUNT(store_number) AS "Unassigned"
            FROM
                activation, precheck
            WHERE
                activation.store_number = precheck.store_number_precheck
            AND
                activation_is_tonight = 1
            AND
                activation_in_progress = 0
            AND
                assigned_sa_tech = 'Unassigned'
SQL;
    $result = $dbconn->query($sql);
    foreach($result as $counter) {
        $_SESSION['unassigned'] = $counter["Unassigned"];
    }
    $dbconn = null;
}

function get_current_activations() {
    $dbconn = connect_db();
    $sql = <<<SQL
            SELECT
                store_number,
                sa_tech,
                activation_type,
                current_step,
                time_step_changed,
                start_time
            FROM
                activation
            INNER JOIN
                store_annals
            ON
                activation.store_number = store_annals.store_number_records
            WHERE 
                activation_in_progress = '1'
            ORDER BY start_time
SQL;
    $result = $dbconn->query($sql);
    $trtd = '<tr><td>';
    $tdtd = '</td><td>';
    $tdtr = '</td></tr>';
    $controller = 0;

    foreach($result as $activation) {
        $controller++;
        
        $sa_tech = $activation['sa_tech'];
        $store_number = $activation['store_number'];
        $activation_type = $activation['activation_type'];
        $current_step = $activation['current_step'];
        $step_data = get_current_step($activation_type, $current_step);
        $step_time = time() - $activation['time_step_changed'];
        $start_time = time() - $activation['start_time'];
        
        $td_total_id = '</td><td><div id = "total_div_' . $controller . '" data-total = "' . $start_time . '"></div>';
        $td_step_id = '</td><td><div id = "step_div_' . $controller . '" data-step = "' . $step_time . '"></div>';

        
        echo $trtd . $sa_tech . $tdtd . $store_number . $tdtd . $activation_type . $tdtd . $step_data . $td_step_id . $td_total_id . $tdtr;
    }
    $dbconn = null;
};

function get_on_deck() {
    $dbconn = connect_db();
    $sql = <<<SQL
            SELECT
                store_number, assigned_sa_tech, activation_type, activation_time_scheduled
            FROM
                activation, store_annals, precheck
            WHERE
                activation.store_number = store_annals.store_number_records
            AND
                activation.store_number = precheck.store_number_precheck
            AND
                activation_is_tonight = 1
            AND
                activation_in_progress = 0
			AND
				activation_completed = 0
            AND
                assigned_sa_tech != 'Unassigned'
            ORDER BY activation_time_scheduled
SQL;
    $result = $dbconn->query($sql);
    $trtd = '<tr><td>';
    $tdtd = '</td><td>';
    $tdtr = '</td></tr>';
    $controller = 0;
    foreach($result as $activation) {
        $controller++;
        
        $sa_tech = $activation['assigned_sa_tech'];
        $store_number = $activation['store_number'];
        $activation_type = $activation['activation_type'];
        $until_start_time = $activation['activation_time_scheduled'];
        
        $td_until_id = '</td><td><div id = "until_start_div_' . $controller . '" data-until = "' . $until_start_time . '"></div>';
        
        echo $trtd . $sa_tech . $tdtd . $store_number . $tdtd . $activation_type . $td_until_id . $tdtr;

    };
}

function get_unassigned() {
    $dbconn = connect_db();
    $sql = <<<SQL
            SELECT
                store_number, activation_type, activation_time_scheduled
            FROM
                activation, store_annals, precheck
            WHERE
                activation.store_number = store_annals.store_number_records
            AND
                activation.store_number = precheck.store_number_precheck
            AND
                activation_is_tonight = 1
            AND
                activation_in_progress = 0
            AND
                assigned_sa_tech = 'Unassigned'
SQL;
    $result = $dbconn->query($sql);
    $trtd = '<tr><td>';
    $tdtd = '</td><td>';
    $tdtr = '</td></tr>';
    $controller = 0;
    foreach($result as $activation) {
        $controller++;
        
        $store_number = $activation['store_number'];
        $activation_type = $activation['activation_type'];
        $until_start_time = $activation['activation_time_scheduled'];
        
        $td_until_id = '</td><td><div id = "until_unassigned_div_' . $controller . '" data-until = "' . $until_start_time . '"></div>';
        
        echo $trtd . $store_number . $tdtd . $activation_type . $td_until_id . $tdtr;
    };
}

function get_current_step($type,$step){
    $dbconn = connect_db();
    $sql = <<<SQL
            SELECT
                step_data
            FROM
                activation_steps
            WHERE
                activation_type = :type
            AND
                step_number = :step
SQL;
    $result = $dbconn->prepare($sql);
    $result->execute( array( ':type'=>$type, ':step'=>$step ));
    foreach($result as $step) {
        $this_step_data = $step['step_data'];
        return $this_step_data;
    }
    $dbconn = null;
}