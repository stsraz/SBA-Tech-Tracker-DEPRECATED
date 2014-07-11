<?php
session_start();

function sba_abort_callback() {
    check_ajax_referer( 'sba-security-string', 'security');
    $callback_type = $_POST['callback_type'];
    switch($callback_type) {
        case 0:
            $header = $_SESSION['header'];
            $start_time = "Activation Start Time: " . gmdate("H:i:s", $_SESSION['start_time']);
            $end_time = "Activation End Time: " . gmdate("H:i:s", $_SESSION['end_time']);
            $total_time = $_SESSION['end_time'] - $_SESSION['start_time'];
            $total_time = gmdate("H:i:s", $total_time);
            $total = "Total Activation Time: " . $total_time;
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
STR1;
            //show_form();
            die();
            break;
    }
}
add_action( 'wp_ajax_abort_callback', 'sba_abort_callback' );