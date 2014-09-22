<?php

include_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-tab.php';
include_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-accordion.php';
include_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-button-bar.php';
include_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-select-menu.php';
include_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-table.php';
include_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-database.php';
include_once get_stylesheet_directory() . '/sba_techtracker/PHP/Objects/sba-sbaxml.php';

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
	public $logic_object;
	public $sbaxml_object;
	public $my_interface='<div id="tabs"><ul><li><a href="#start"><span>Start Page</span></a></li><li><a href="#tracker"><span>Tech Tracker</span></a></li><li><a href="#precheck"><span>Precheck</span></a></li><li><a href="#view"><span>View Activation</span></a></li><li><a href="#summary"><span>Activation Summary</span></a></li></ul><div id="start"></div><div id="tracker"></div><div id="precheck"></div><div id="view"></div><div id="summary"></div></div>';
	public $tab_divs=array('start','tracker','precheck','view','summary');
	
	public function __construct(Logic $logic) {
		$this->logic_object=$logic;
		$this->sbaxml_object=new SBAXML();
	}
	
	public function populate_my_dom() {
		// Create the tabs, populate them, and insert them into the dom
		for($i=0;$i<count($this->tab_divs);$i++) {
			$temp_tab=new Tabs($i);
			$div_pos='<div id="' . $this->tab_divs[$i] . '"></div>';
			$insert=$temp_tab->get_tab();
			$string=$this->my_interface;
			$tab_to_interface=$this->logic_object->into_div($div_pos, $insert, $string);
			$this->my_interface=$tab_to_interface;
		}
		// Return the dom back to the router, then to the callback
		return $this->my_interface;
	}
}

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
}

?>