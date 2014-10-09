<?php
function set_sba_cookies() {
	if ( !is_admin() && !isset($_COOKIE['activation_in_progress'])) {
		$temp_info=array();
		$temp_info=get_user_activation_information();
		// 86400 = 1 day
		setcookie('store_number',$temp_info['store_number'], time() + (86400 * 30),COOKIEPATH,COOKIE_DOMAIN,false);
		setcookie('scheduled',$temp_info['scheduled'], time() + (86400 * 30),COOKIEPATH,COOKIE_DOMAIN,false);
	}
}
add_action( 'init', 'set_sba_cookies');

function get_user_activation_information() {
	$temp_info=array();
	$username=wp_get_current_user()->user_login;
	$what='store_number,scheduled';
	$from='activation_information,precheck,activation_times';
	$where='assigned_tech="'.$username.'" AND activation_information.id=precheck.id_precheck AND activation_information.id=activation_times.id_times';
	$order_by='store_number';
	$result=Database::select($what,$from,$where,$order_by);
	foreach($result as $user_info) {
		$temp_info['store_number']=$user_info['store_number'];
		$temp_info['scheduled']=$user_info['scheduled'];
	}
	return $temp_info;
}
?>