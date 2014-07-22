<?php
session_start();

//check_logged_in();

function sba_edit_callback() {
    check_ajax_referer( 'sba-security-string', 'security');
    $callback_type = $_POST['callback_type'];
    switch($callback_type) {
        case 0:	//Displays the initial edit choices
			$welcome = "Welcome " . $_SESSION['sa_tech_name'] . " !";
			$instructions = "Please select an option:";
echo <<<StringOne
	<p>$welcome</p><br>
	<p>$instructions</p><br>
	
	<button id="single_activation_button">Edit a Single Activation</button>
	<button id="multi_activation_button">Edit a Range of Activations</button>
StringOne;
        die();
        break;
		case 1:	//Displays the initial single activation select menu
			$select_default = '';
			get_single_activation_list($select_default);
		die();
		break;
		case 2:	//Get the selected single activation for editing
			$select_default = $_POST['selected_single_store'];
			get_single_activation_list($select_default);
			get_selected_single_activation();
		die();
		break;
		case 3:	//Save new data from the edit page
			$selected_store = $_POST['selected_single_store'];
			$new_eon = $_POST['new_eon'];
			$new_ops = $_POST['new_ops'];
			$new_bridge = $_POST['new_bridge'];
			$new_tech = $_POST['new_tech'];
			save_edited_data($selected_store,$new_eon,$new_ops,$new_bridge,$new_tech);
		die();
		break;
    }
}
add_action( 'wp_ajax_edit_callback', 'sba_edit_callback' );

function get_single_activation_list($select_default) {
echo <<<StringOne
	<button id="multi_activation_button">Edit a Range of Activations</button><br>
	<p>Select a store number: </p>
	<select id='single_activation_select'>
		<option selected value='$select_default'>$select_default</option>
StringOne;
	$dbconn = connect_db();
$sql = <<<StringOne
	SELECT
		store_number,
		start_timestamp_gmt
	FROM
		activation
	WHERE
		activation_completed = "0"
	ORDER BY
		store_number
StringOne;
	$result = $dbconn->query($sql);
	foreach($result as $list){
		if($list['store_number']!=$select_default){
			echo "<option value='" . $list['store_number'] . "'>" . $list['store_number'] . " - " . convert_to_time($list['start_timestamp_gmt']) . "</option>";
		};
	}
	echo "</select>";
}

function get_selected_single_activation() {
	$store_number = $_POST['selected_single_store'];
	$dbconn = connect_db();
echo <<<StringOne
	<table id='single_edit_table'>
		<thead>
			<tr>
				<td>Activation Date</td>
				<td>Eon</td>
				<td>Ops Console</td>
				<td>Bridge Access Code</td>
				<td>Assigned SA Tech</td>
			</tr>
		</thead>
		<tbody>
			<tr>
StringOne;
$sql = <<<StringTwo
	SELECT
		start_timestamp_gmt,
		eon_number,
		ops_console_number,
		bridge_access_code,
		assigned_sa_tech
	FROM
		activation,precheck
	WHERE
		activation.store_number = precheck.store_number_precheck
	AND
		activation.store_number = :storenumber
StringTwo;
	$result=$dbconn->prepare($sql);
	$result->execute(array(':storenumber'=>$store_number));
	foreach($result as $edit) {
		echo "<td>" . convert_to_time($edit['start_timestamp_gmt']) . "</td>";
		echo "<td><textarea id = 'selected_eon_number' cols = '4' rows = '1'>" . $edit['eon_number'] . "</textarea></td>";
		echo "<td><textarea id = 'selected_ops_console_number' cols = '6' rows = '1'>" . $edit['ops_console_number'] . "</textarea></td>";
		echo "<td><textarea id = 'selected_bridge_access_code' cols = '6' rows = '1'>" . $edit['bridge_access_code'] . "</textarea></td>";
		get_tech_list($edit['assigned_sa_tech']);
	}
	echo "</tr></tbody></table><br><button id='submit_single_changes'>Save Changes</button>";
}

function get_tech_list($assigned_tech){
echo <<<StringOne
	<td><select id='selected_assigned_sa_tech'>
		<option selected value='$assigned_tech'>$assigned_tech</option>
StringOne;
	$dbconn = connect_db();
$sql = <<<StringTwo
	SELECT * FROM
		sa_tech
	ORDER BY
		sa_tech_name		
StringTwo;
	$result = $dbconn->query($sql);
	foreach($result as $tech){
		if($tech['sa_tech_name']!=$assigned_tech){
			echo "<option value='" . $tech['sa_tech_name'] . "'>" . $tech['sa_tech_name'] . "</option>";
		};
	}
	echo "</select></td>";
}

function save_edited_data($selected_store,$new_eon,$new_ops,$new_bridge,$new_tech) {
	$dbconn = connect_db();
$sql = <<<StringOne
	UPDATE
		activation,precheck
	SET
		eon_number = :neweon,
		ops_console_number = :newops,
		bridge_access_code = :newbridge,
		assigned_sa_tech = :newtech
	WHERE
		activation.store_number = :selectedstore
	AND
		activation.store_number = precheck.store_number_precheck
StringOne;
	$result = $dbconn->prepare($sql);
	$result->execute(array(':selectedstore'=>$selected_store,':neweon'=>$new_eon,':newops'=>$new_ops,':newbridge'=>$new_bridge,':newtech'=>$new_tech));
}
