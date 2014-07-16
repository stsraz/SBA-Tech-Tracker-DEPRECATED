<?php
session_start();

function check_logged_in() {
	// Check if the user is logged in. If so, get user display name from Wordpress.  If not, redirect to the Wordpess login page.
	if ( !is_user_logged_in() ) {
		auth_redirect();
	}
	$current_user = wp_get_current_user();
	$sa_tech_name = $current_user->user_login;
	$_SESSION ['sa_tech_name'] = $sa_tech_name;
}

function set_time_zone() {
	$dbconn = connect_db();
	
	$sql = <<<StringOne
		SELECT
			location
		FROM
			sa_tech
		WHERE
			sa_tech_name = :satech
StringOne;
	$result = $dbconn->prepare($sql);
	$result->execute( array( ':satech'=>$_SESSION['sa_tech_name'] ));
	
	foreach($result as $sa_tech) {
		if($sa_tech['location'] == 'Phoenix'){
			//echo $sa_tech['location'];
			$_SESSION['time_zone'] = 'America/Phoenix';
		}
		else {
			$_SESSION['time_zone'] = 'America/Denver';
		}
	}
}

function connect_db() {
	//A function to be called when access to the database is needed.
	$user = 'sba_techtracker';
	$pass = 'sba_techtracker_password';
	$dbconn = new PDO( 'mysql:host=localhost;dbname=sba_techtracker', $user, $pass );
	return $dbconn;
}

function sba_callback_function() {
    //A function for controlling the response of POST calls
    
    //Checks the source of the ajax call.
    check_ajax_referer( 'sba-security-string', 'security');

    //Pull what case to select for the switch statement from the post queue.
    $callback_type = $_POST[ 'callback_type' ];

    switch($callback_type) {
        case 0:
        //Displays the welcome screen on login
            $welcome = "Welcome "  . $_SESSION['sa_tech_name'] . " !";
            echo <<<StringOne
                <table style = 'width: 100%;'>
                    <thead>
                        <tr><td><div id='welcome'>$welcome</div></td></tr></thead>
                    <tbody>
                        <tr>
                            <td><div>Please Select Your Store Number</div></td></tr>
                        <tr>
                            <td style = 'border-style: solid; border-color: green; border-width: thick; text-align: center; width: 33%;'>Activation in Progress</td>
                            <td style = 'border-style: solid; border-color: red; border-width: thick; text-align: center; width: 33%;'>Activation Past Start Time</td>
                            <td style = 'border-style: solid; border-color: yellow; border-width: thick; text-align: center; width: 33%;'>Activation Waiting for SA Tech</td></tr>
                        <tr>
                            <td>Store Number: </td>
                            <td><select id = 'pending_select' required><option></option>
StringOne;
            echo get_pending_stores();
            echo <<<StringTwo
                </select></td></tr></tbody></table>
                <div id = 'preview_table_div'></div>
                <input type = 'submit' id = 'start_activation' value = 'Start Activation'>
StringTwo;
            die();
            break;
        case 1:
        //Previews store information on welcome screen
            $store = $_POST[ 'store' ];
            preview_store($store);
            die();
            break;
        case 2:
        //Displays tracker framework
            $store = $_POST[ 'store' ];
            $time = $_POST[ 'time' ];
            start_activation($store, $time);
            die();
            break;
        case 3:
        //Store current times before changing steps
            $store = $_POST['store' ];
            $step = $_POST[ 'step' ];
            $time = $_POST[ 'time' ];
            store_time($store,$step,$time);
            die();
            break;
        case 4:
            die();
            break;
        case 5:
        //Ends the activation
            $store = $_POST['store'];
            $end_time = $_POST['end'];
			$times_array = json_decode($_POST['json_times']);
            finish_activation($store,$end_time,$times_array);
            die();
            break;
        case 6:
        //Returns the activation steps
            $activation_type = $_SESSION['activation_type'];
            $dbconn = connect_db();
            $sql = 'SELECT
                        step_number,
                        step_data
                    FROM
                        activation_steps
                    WHERE
                        activation_type = :activationtype';
            $result = $dbconn->prepare($sql);
            $result->execute( array(':activationtype'=>$activation_type));
            $steps = array();
            $steps = $result->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($steps);
            die();
            break;
        case 7:
            $dbconn = connect_db();
            $store = $_POST['store'];
            $help = $_POST['help'];
			$help_reason = $_POST['help_reason'];
            $help_time = $_POST['help_time'];
            $sql = 'UPDATE
                        activation
                    SET
                        help = :help,
                        help_time = :helptime,
                        help_reason = :help_reason
                    WHERE
                        store_number = :store';
            $result = $dbconn->prepare($sql);
            $result->execute( array( ':help'=>$help, ':helptime'=>$help_time, ':store'=>$store, ':help_reason'=>$help_reason ));
            die();
            break;
    }
}
add_action( 'wp_ajax_callback_function', 'sba_callback_function');

function finish_activation($store,$end_time,$times_array) {
    $_SESSION['end_time'] = $end_time;
    $dbconn = connect_db();
    $sql = 'UPDATE
                activation
            INNER JOIN
                store_annals
            ON
                activation.store_number = store_annals.store_number_records
            SET
				activation_is_tonight = 0,
                activation_in_progress = 0,
                activation_completed = 1,
                end_time = :endtime
            WHERE
                store_number = :store';
    $result = $dbconn->prepare($sql);
    $result->execute( array(':endtime'=>$end_time, ':store'=>$store));
	
	$sql = <<<STRING1
				UPDATE
					store_annals
				SET
					1_time = :1time,
					2_time = :2time,
					3_time = :3time,
					4_time = :4time,
					5_time = :5time,
					6_time = :6time,
					7_time = :7time,
					8_time = :8time,
					9_time = :9time,
					10_time = :10time,
					11_time = :11time,
					12_time = :12time,
					13_time = :13time,
					14_time = :14time
				WHERE
					store_number_records = :store				
STRING1;
	$result = $dbconn->prepare($sql);
	$result->execute( array( ':1time'=>$times_array[0], ':2time'=>$times_array[1], ':3time'=>$times_array[2], ':4time'=>$times_array[3], ':5time'=>$times_array[4], ':6time'=>$times_array[5], ':7time'=>$times_array[6], ':8time'=>$times_array[7], ':9time'=>$times_array[8], ':10time'=>$times_array[9], ':11time'=>$times_array[10], ':12time'=>$times_array[11], ':13time'=>$times_array[12], ':14time'=>$times_array[13], ':store'=>$store ));
    $dbconn = null;
}

function get_pending_stores() {
    try {
            //Open a database connection
            $dbconn = connect_db();

            //Query the database table
            $sql = 'SELECT
                        store_number,
                        activation_past_start,
                        activation_waiting,
                        activation_completed,
                        activation_in_progress,
                        assigned_sa_tech
                    FROM
                         activation,
                         precheck
					WHERE
						activation.store_number = precheck.store_number_precheck
					AND
						activation_is_tonight = "1"
					ORDER BY assigned_sa_tech';
            $result = $dbconn->query($sql);

            //Populate the dropdown with the results
            foreach( $result as $pending ) {
            	if($pending['activation_in_progress'] == 1){
            		echo '<option style = "border-style: solid; border-color: green; border-width: thick;" value="' . $pending['store_number'] . '">' . $pending['store_number'] . ' - ' . $pending['assigned_sa_tech'] . '</option>';
            	}
				elseif($pending['activation_past_start'] == 1){
					echo '<option style = "border-style: solid; border-color: red; border-width: thick;" value="' . $pending['store_number'] . '">' . $pending['store_number'] . ' - ' . $pending['assigned_sa_tech'] . '</option>';
				}
				elseif($pending['activation_waiting'] == 1){
					echo '<option style = "border-style: solid; border-color: yellow; border-width: thick;" value="' . $pending['store_number'] . '">' . $pending['store_number'] . ' - ' . $pending['assigned_sa_tech'] . '</option>';
				}
				else{
					echo '<option style = "border-style: solid; border-color: white; border-width: thick;" value="' . $pending['store_number'] . '">' . $pending['store_number'] . ' - ' . $pending['assigned_sa_tech'] . '</option>';
				}
            }
            
            //Close the database connection.
            $dbconn = null;
    }
    //If try fails
    catch(PDOException $e) {
            print 'Error!: ' . $e->getMessage() . '<br>';
    }
}

function preview_store($selected_store) {
//Returns the store preview
    echo <<<StringOne
        <table id='preview_table'>
            <thead>
                <tr>
                    <td>Activation Start Time</td>
                    <td>Activation Type</td>
                    <td>Store Number</td>
                    <td>Eon Number</td>
                    <td>Location</td>
                    <td>Field Tech</td></tr>
                <tr>
StringOne;
        try {
            //Open a database connection
            $dbconn = connect_db();

            //Query the database table
            $sql = 'SELECT 
                        start_timestamp_gmt,
                        activation_type,
                        eon_number,
                        store_city,
                        store_state,
                        field_tech_name
                    FROM
                        activation,store
					WHERE
                        activation.store_number = store.store_number
                    AND
                        activation.store_number = :storenumber';
                $result = $dbconn->prepare( $sql );
                $result->execute( array( ':storenumber'=>$selected_store ));

                //Display the preview information
                foreach( $result as $preview ) {
                    $time = convert_to_time( $preview[ 'start_timestamp_gmt' ] );
                    echo '<td>' . $time . '</td>';
					echo '<td>' . $selected_store . '</td>';
                    echo '<td>' . $preview[ 'activation_type' ] . '</td>';
                    echo '<td>' . $preview[ 'eon_number' ] . '</td>';
                    echo '<td>' . $preview[ 'store_city' ] . ', ' . $preview[ 'store_state' ] . '</td>';
					echo '<td>' . $preview[ 'field_tech_name' ] . '</td>';
					$_SESSION['field_tech'] = $preview['field_tech_name'];
                }

                //Close the database connection.
                $dbconn = null;
        }
        catch(PDOException $e) {
            print 'Error!: ' . $e->getMessage() . '<br>';
        }
}

function start_activation($store, $time) {
//Start the activation
    $_SESSION['store_number'] = $store;
    $_SESSION['start_time'] = $time;
    $tech = $_SESSION[ 'sa_tech_name' ];

    //Open a database connection
    $dbconn = connect_db();

    //Update tech and start times
    try {
        $sql = 'UPDATE
                    activation,store_annals
                SET
                    current_step = 1,
                    activation_past_start = 0,
                    activation_waiting = 0,
                    activation_in_progress = 1,
                    sa_tech = :tech,
                    start_time = :start
                WHERE
					activation.store_number = store_annals.store_number_records
				AND
                    activation.store_number = :storenumber';
        $result = $dbconn->prepare( $sql );
        $result->execute( array( ':tech'=>$tech, ':start'=>$time, ':storenumber'=>$store ));
    }
    catch(PDOException $e) {
            print 'Error!: ' . $e->getMessage() . '<br>';
    }

    //Pull activation information
    try {					
        $sql = 'SELECT 
                    eon_number,
                    ops_console_number,
                    activation_type,
                    primary_access_vendor,
                    store.store_city,
                    store.store_state
                FROM
                    activation
                INNER JOIN
                    store
                ON
                    activation.store_number = store.store_number
                WHERE
                    activation.store_number = :storenumber';
        $result = $dbconn->prepare( $sql );
        $result->execute( array( ':storenumber'=>$store ));

        foreach( $result as $info ) {
            $_SESSION[ 'eon_number' ] = $info[ 'eon_number' ];
            $_SESSION[ 'ops_console_number' ] = $info[ 'ops_console_number' ];
            $_SESSION[ 'activation_type' ] = $info[ 'activation_type' ];
            $_SESSION[ 'primary_access_vendor' ] = $info[ 'primary_access_vendor' ];
            $_SESSION[ 'header' ] = $store . ' | ' . $info['eon_number'] . ' | ' . $info['ops_console_number'] . ' | ' . $info['store_city'] . ', ' . $info['store_state'] . ' - ' . $info['activation_type'];
        }

        //Close the database connection
        $dbconn = null;
    }
    catch(PDOException $e) {
        print 'Error!: ' . $e->getMessage() . '<br>';
    }

    $header = $_SESSION['header'];

    echo "<div id = 'tech_name'>" . $tech . "</div>";

    echo <<<StringOne
        <table id = 'header_info'>
            <tr>
                <td><div id = 'header_div'>$header</div></td>
                <td><div id = 'total_timer'></div></td></tr></table><br></br>
        <table id = 'buttons_one'>
            <tr>
            <td>
                <div id = 'abort_div'></div></td>
            <td>
                <div id = 'pause_div'></div>
                <div id = 'resume_div'></div></td></tr></table><br><br>
        <table id = 'timers'>
            <tr>
                <td>
                    <div id = 'step_timer'></div></td>
                <td>
                    <div id = 'escalate_timer'></div></td></tr></table>
        <div id = 'step_div'></div><br><br>
        <table id = 'buttons_two'>
            <tr>
                <td>
                    <div id = 'back_div'></div></td>
                <td>
                    <div id = 'help_div'></div>
                    <div id = 'help_timer_div'></div></td>
                <td>
                    <div id = 'next_div'></div>
                    <div id = 'finish_div'></div></td></tr></table>
        <iframe src = '' seamless style = 'width: 100%; height: 500px;'></iframe>
StringOne;
}

function convert_to_time($seconds) {
	date_default_timezone_set( $_SESSION['time_zone'] );
    $format = '%a %b %e %r %Z';
    $time = strftime($format, $seconds);
    return $time;
};

function store_time($store,$step,$current_time) {
    $total_time = 0;
    $start_time = 0;
    $dbconn = connect_db();
    $sql = <<<SQL
            SELECT
                start_time
            FROM
                store_annals
            WHERE
                store_number_records = :store
SQL;
    $result = $dbconn->prepare($sql);
    $result->execute( array( ':store'=>$store ));
    foreach($result as $time) {
        $start_time = $time['start_time'];
    }
    $total_time = $current_time - $start_time;
    
    $sql = <<<SQL
            UPDATE
                activation
            INNER JOIN
                store_annals
            ON
                activation.store_number = store_annals.store_number_records
            SET
                current_step = :step,
                time_step_changed = :currenttime,
                total_activation_time = :totaltime
            WHERE
                store_number = :store
SQL;
    $result = $dbconn->prepare($sql);
    $result->execute( array( ':store'=>$store, ':step'=>$step, ':totaltime'=>$total_time, ':currenttime'=>$current_time ));
}