<?php
session_start();

function sba_finish_callback() {
    check_ajax_referer( 'sba-security-string', 'security');
    $callback_type = $_POST['callback_type'];
    switch($callback_type) {
        case 0:
            $header = $_SESSION['header'];
            $start_time = "Activation Start Time: " . convert_to_time($_SESSION['start_time']);
            $end_time = "Activation End Time: " . convert_to_time($_SESSION['end_time']);
            $total_time = $_SESSION['end_time'] - $_SESSION['start_time'];
            $total_time = gmdate("H:i:s", $total_time);
            $total = "Total Activation Time: " . $total_time;
			$field_tech = $_SESSION['field_tech'];
            echo <<<STR1
                <table>
                    <tr>
                        <td>$header</td>
                    </tr>
                    <tr>
                        <td>$start_time</td>
                    <tr>
                        <td>$end_time</td>
                    </tr>
                    <tr>
                        <td>$total</td>
                    </tr>
                </table>
                <br><br>
                <p>Please rate your experience with $field_tech on a scale of 1 (Low) to 5 (High)</p>
                <br>
                <form id = 'rating'>
                	<input type = 'radio' name = 'rating' value = '1.0'>1 -- My tech was horrible!<br>
                	<input type = 'radio' name = 'rating' value = '2.0'>2<br>
                	<input type = 'radio' name = 'rating' value = '3.0'>3 -- My tech was okay...<br>
                	<input type = 'radio' name = 'rating' value = '4.0'>4<br>
                	<input type = 'radio' name = 'rating' value = '5'>5 -- My tech was a stud!<br><br><br>
					<textarea id = 'comments' cols = '40' rows = '5'>Tell us about your tech here!</textarea><br><br><br>
               	</form>
               	<input type = 'submit' id = 'send_rating' value = 'Send Rating'>
STR1;
            die();
            break;
		case 1:
			$field_tech = $_SESSION['field_tech'];
			$tech_rating = $_POST['tech_rating'];
			$comments = $_POST['comments'];
			$dbconn = connect_db();
			$sql = <<<StringOne
				INSERT INTO
					ft_ratings (field_tech_name,field_tech_rating,comments)
				VALUES
					(:ftn,:ftr,:comments)
StringOne;
			$result = $dbconn->prepare($sql);
			$result->execute( array( ':ftn'=>$field_tech, ':ftr'=>$tech_rating, ':comments'=>$comments ));
			die();
			break;
    }
}
add_action( 'wp_ajax_finish_callback', 'sba_finish_callback' );