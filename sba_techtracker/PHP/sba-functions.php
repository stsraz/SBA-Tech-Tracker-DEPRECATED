<?php
session_start();
if(!$_SESSION['my_interface']) {
	$_SESSION['my_interface']='<div id="tabs"><ul><li><a href="#start"><span>Start Page</span></a></li><li><a href="#tracker"><span>Tech Tracker</span></a></li><li><a href="#precheck"><span>Precheck</span></a></li><li><a href="#view"><span>View Activation</span></a></li><li><a href="#summary"><span>Activation Summary</span></a></li></ul><div id="start"></div><div id="tracker"></div><div id="precheck"></div><div id="view"></div><div id="summary"></div></div>';
}
function sba_callback() {
//A callback function for responding to Javascript Ajax calls

    //Checks the source of the ajax call.
    check_ajax_referer( 'sba-security-string', 'security');
	
	$action=$_POST['type'];
	$username=wp_get_current_user()->user_login;
	$logic=new Logic($username);
	$presenter=new Presenter($logic);
	$router=new Router($presenter);
	$response=$router->do_action($action);
	echo $response;
	die();
}
add_action( 'wp_ajax_sba_callback', 'sba_callback');

class Router {
// A class that routes the given POST request to the correct presenter method
	public $presenter;
	
	public function __construct(Presenter $presenter) {
		$this->presenter=$presenter;
		
	}
	public function do_action($action) {
        $response = $this->presenter->$action();
		return $response;
	}
}

class Presenter {
// A class that displays data on the page and hold the methods for the Router object.
	// Properties
	public $logic_object;
	public $sbaxml_object;
	public $tab_divs=array('start','tracker','precheck','view','summary');
	//Methods
	public function __construct(Logic $logic) {
		$this->logic_object=$logic;
		$this->sbaxml_object=new SBAXML();
	}
	public function populate_my_dom() {
		// Create the tabs, populate them, and insert them into the dom
		for($i=0;$i<count($this->tab_divs);$i++) {
			$temp_tab=new Tabs($i);
			$div_pos='<div id="' . $this->tab_divs[$i] . '"></div>';
			$insert=$temp_tab->get_tab_string();
			$string=$_SESSION['my_interface'];
			$tab_to_interface=$this->logic_object->into_div($div_pos, $insert, $string);
			$_SESSION['my_interface']=$tab_to_interface;
		}
		$this->show_user_store();
		// Return the dom back to the router, then to the callback
		return $_SESSION['my_interface'];
		
	}
	public function show_user_store() {
		$div_array=array('act_info_zero3','act_info_zero4','act_info_zero5','act_info_one4','act_info_one5','act_info_one6','act_info_one7','act_info_two4','act_info_two5','act_info_two6','act_info_two7');
		$data_array=array('store_number','scheduled','type','eon','ops_console','bridge','pin','assigned_tech','primary_vendor','backup_carrier','revisit');
		$user_store=array();
		$user_store=$this->logic_object->get_store('user');
		if($user_store['revisit']=='0') {
			$user_store['revisit']='No';
		}
		if($user_store['revisit']=='1') {
			$user_store['revisit']='Yes';
		}
		$user_store['pin']=$this->show_bridge_pin($temp_requested_store['bridge']);
		for($i=0;$i<count($user_store);$i++) {
			$div_pos="<div id='" . $div_array[$i] . "'></div>";
			$insert="<div id='" . $div_array[$i] . "'>" . $user_store[$data_array[$i]] . "</div>";
			$string=$_SESSION['my_interface'];
			$data_to_interface=$this->logic_object->into_div($div_pos,$insert,$string);
			$_SESSION['my_interface']=$data_to_interface;
		}
	}
	public function show_requested_store() {
		$div_array=array('act_info_zero3','act_info_zero4','act_info_zero5','act_info_one4','act_info_one5','act_info_one6','act_info_one7','act_info_two4','act_info_two5','act_info_two6','act_info_two7');
		$data_array=array('store_number','scheduled','type','eon','ops_console','bridge','pin','assigned_tech','primary_vendor','backup_carrier','revisit');
		$requested_number=$_POST['requested'];
		$temp_requested_store=array();
		$temp_requested_store=$this->logic_object->get_store($requested_number);
		if($temp_requested_store['revisit']=='0') {
			$temp_requested_store['revisit']='No';
		}
		if($temp_requested_store['revisit']=='1') {
			$temp_requested_store['revisit']='Yes';
		}
		$temp_requested_store['pin']=$this->show_bridge_pin($temp_requested_store['bridge']);
		$requested_store=array();
		for($i=0;$i<count($temp_requested_store);$i++) {
			$div=$div_array[$i];
			$data_type=$data_array[$i];
			$data=$temp_requested_store[$data_type];
			$requested_store[$i]['div']=$div;
			$requested_store[$i]['data']=$data;
			$requested_store[$i]['data_type']=$data_type;
		}
		$requested_json=json_encode($requested_store);
		return $requested_json;
	}
	public function show_bridge_pin($bridge) {
		$temp_pin=$this->sbaxml_object->get_pin($bridge);
		return $temp_pin;
	}
	public function keep_alive() {
		$temp_time=time();
		return $temp_time;
	}
}
require_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-tab.php';
require_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-accordion.php';
require_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-button-bar.php';
require_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-select-menu.php';
require_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-table.php';

class Logic {
// A class that handles data retrieval and manipulation
	public $username;
	
	public function __construct($username) {
		$this->username=$username;
	}
	public function into_div($div_pos,$insert,$string) {
		$new_string=str_replace($div_pos,$insert,$string);
		return $new_string;
	}
	public function get_upcoming_stores_list() {
		$store_list=array();
		$iterator=0;
		$what='store_number,assigned_tech';
		$from='activation_information,activation_status,precheck';
		$where='activation_information.id=activation_status.id_status AND activation_information.id=precheck.id_precheck AND activation_status.tonight=1';
		$order_by='store_number';
		$result=Database::select($what,$from,$where,$order_by);
		foreach($result as $store) {
			if($store['assigned_tech']==$this->username) {
				$store_list[$iterator]['value']=$store['store_number'] . '" selected="selected';
				
			}
			else {
				$store_list[$iterator]['value']=$store['store_number'];
			}
			$store_list[$iterator]['display']=$store['store_number'] . ' - ' . $store['assigned_tech'];
			$iterator++;
		}
		return $store_list;
	}
	public function get_store($type) {
		if($type=='user') {
			$where='precheck.assigned_tech="'.$this->username.'"';
		}
		else {
			$where='activation_information.store_number="'.$type.'"';
		}
		$store_information=array();
		$what='store_number,scheduled,type,eon,ops_console,bridge,assigned_tech,primary_vendor,backup_carrier,revisit';
		$from='activation_information,activation_times,activation_status,precheck';
		$where .= 'AND activation_information.id=activation_times.id_times AND activation_information.id=precheck.id_precheck AND activation_information.id=activation_status.id_status';
		$order_by='store_number';
		$result=Database::select($what,$from,$where,$order_by);
		foreach($result as $store) {
			$store_information['store_number']=$store['store_number'];
			$store_information['scheduled']=$store['scheduled'];
			$store_information['type']=$store['type'];
			$store_information['eon']=$store['eon'];
			$store_information['ops_console']=$store['ops_console'];
			$store_information['bridge']=$store['bridge'];
			$store_information['assigned_tech']=$store['assigned_tech'];
			$store_information['primary_vendor']=$store['primary_vendor'];
			$store_information['backup_carrier']=$store['backup_carrier'];
			$store_information['revisit']=$store['revisit'];
		}
		return $store_information;
	}
}
require_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-database.php';
require_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-sbaxml.php';
?>