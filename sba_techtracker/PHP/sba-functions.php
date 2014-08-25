<?php

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
	public $my_logic;
	public $my_interface='<div id="tabs"><ul><li><a href="#start"><span>Start Page</span></a></li><li><a href="#tracker"><span>Tech Tracker</span></a></li><li><a href="#precheck"><span>Precheck</span></a></li><li><a href="#view"><span>View Activation</span></a></li><li><a href="#summary"><span>Activation Summary</span></a></li></ul><div id="start"></div><div id="tracker"></div><div id="precheck"></div><div id="view"></div><div id="summary"></div></div>';
	
	public function __construct(Logic $logic) {
		$this->my_logic=$logic;
	}
	
	public function populate_my_dom() {
		$start_tab=new Tabs(0);
		$tracker_tab=new Tabs(1);
		$precheck_tab=new Tabs(2);
		$view_tab=new Tabs(3);
		$summary_tab=new Tabs(4);
				
		$tab_array=array($start_tab,$tracker_tab,$precheck_tab,$view_tab,$summary_tab);
		foreach($tab_array as $tab) {
			$tous=$tab->set_tables();
			$div_pos='<div id="' . $tous['handle'] . '"></div>';
			$insert=$tous['insert'];
			$string=$this->my_interface;
			$tab_div=$this->my_logic->into_div($div_pos, $insert, $string);
			$this->my_interface=$tab_div;
		}
		return $this->my_interface;
	}

	public function get_static_data() {
		$static_array=array();
		$static_array=$this->my_logic->get_xml("static_data","div");
		return $static_array;
	}
}

class Table extends Presenter {
// A class that makes custom table objects
	public $tcols;
	public $trows;
	public $tdivs;
	public $thandle;
	
	public function __construct($cols,$rows,$divs,$div_handle) {
		$this->set_cols($cols);
		$this->set_rows($rows);
		$this->set_divs($divs);
		$this->set_div_handle($div_handle);
	}
	
	public function set_cols($cols) {
		$this->tcols=$cols;
	}
	
	public function set_rows($rows) {
		$this->trows=$rows;
	}
	
	public function set_divs($divs) {
		$this->tdivs=$divs;
	}
	
	public function set_div_handle($div_handle) {
		$this->thandle=$div_handle;
	}
	
	public function build_table() {
		$div_counter=0;
		$table_construct='';
		$table_construct=$table_construct .  '<table class="my_table" id="' . $this->thandle . '_table">';
		for($i=0;$i<$this->trows;$i++) {
			$table_construct=$table_construct .  "<tr>";
				for($z=0;$z<$this->tcols;$z++) {
					$table_construct=$table_construct .  "<td>";
						if($div_counter<$this->tdivs) {
							$table_construct=$table_construct .  "<div id='" . $this->thandle . $div_counter . "'></div>";
							$div_counter++;
						}
					$table_construct=$table_construct .  "</td>";
				}
			$table_construct=$table_construct .  "</tr>";
		}
		$table_construct=$table_construct .  "</table>";
		return $table_construct;
	}
}

class Tabs extends Presenter {
// A class that creates the tab structure and populates them with tables.
	public $SBAXML_obj;
	public $tab_prop;

	public $insert;
		
	public function __construct($rti) {
		$this->SBAXML_obj=new SBAXML();
		//Store tab properties
		$this->tab_prop=$this->SBAXML_obj->get_tab($rti);
	}
	
	public function set_tables() {
		$temp_obj;
		$holding_cell=array();
		$tous=array();
		$insert;
		$tab_handle=$this->tab_prop['handle'];
		$div_wrapper_open="<div id='" . $tab_handle . "'>";
		$div_wrapper_close="</div>";
		
		for($i=0;$i<$this->tab_prop['tables'];$i++) {
			$tbl_handle=$this->tab_prop["table".$i]['handle'];
			$tbl_rows=$this->tab_prop["table".$i]['rows'];
			$tbl_cols=$this->tab_prop["table".$i]['cols'];
			$tbl_divs=$this->tab_prop["table".$i]['divs'];
			$temp_obj=new Table($tbl_cols,$tbl_rows,$tbl_divs,$tbl_handle);
			$holding_cell[$i]=$temp_obj->build_table();
		}
		array_unshift($holding_cell,$div_wrapper_open);
		$holding_cell[]=$div_wrapper_close;
		$insert=implode($holding_cell);
		$tous['handle']=$tab_handle;
		$tous['insert']=$insert;
		
		return $tous;
	}
	
}

class Logic {
// A class that handles data retrieval and manipulation
	public $username;
	public $xml_obj;
	
	public function __construct($username) {
		$this->username=$username;
		$this->xml_obj=new SBAXML();
	}

	public function into_div($div_pos,$insert,$string) {
		$new_string=str_replace($div_pos,$insert,$string);
		return $new_string;
	}
	
	public function get_xml($parent,$child) {
		$requested_xml=$this->xml_obj->get_requested_xml($parent,$child);
		return $requested_xml;
	}
	
}

class SBAXML extends Logic{
// A class that creates an object that retrieves and manipulates sba_data.xml
	public $sba_xml;
	public function __construct() {
		//$sba_xml;
		$xml_path='http://localhost/wp-content/themes/hueman-child/sba_techtracker/PHP/sba_data.xml';
		$this->sba_xml=simplexml_load_file($xml_path);
		return $this->sba_xml;
	}
	
	public function get_requested_xml($parent,$child) {
		$temp_array=array();
		$temp_string=$this->sba_xml->$parent->$child;
		for($i=0;$i<count($this->sba_xml->$parent->$child);$i++) {
			foreach($temp_string[$i]->attributes() as $key=>$pair) {
				$temp_array[$i][$key]=$pair;
			}
		};
		$temp_array=json_encode($temp_array);
		return $temp_array;
	}
	
	public function get_location($username) {
		$user_location;
		for($i=0;$i<count($this->sba_xml->user);$i++) {
			$user=$this->sba_xml->user[$i];
			if($user==$username) {
				foreach($this->sba_xml->user[$i]->attributes() as $key=>$pair) {
					$user_location=$pair;
				}
				break;
			}
		}
		return $user_location;
	}

	public function get_tab($rti) {
	//Pulls the requested tab data from the sba_xml doc

		$this_tab=array();	//Holds the tab the function will return

		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->attributes() as $key=>$pair) {
			$this_tab[$key]=$pair;
		}
		for($i=0;$i<count($this->sba_xml->sba_tab_layout->sba_tab[$rti]);$i++) {
			$table_var='table'.$i;
			foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->attributes() as $key=>$pair) {
				$this_tab["table".$i][$key]=$pair;
			}
		}
		return $this_tab;
	}
	
}

abstract class Database {
// An abstract class that holds methods to communicate with the database.
	
	public static function connect() {
		$db_user = 'sba_techtracker';
		$db_pass = 'sba_techtracker_password';

		$dbconn = new PDO('mysql:host=localhost;dbname=sba_techtracker', $db_user, $db_pass);
		return $dbconn;
	}  
    public static function disconnect() {
    	$dbconn = NULL;
    }  
    public static function select($what,$from,$where,$order_by) {
    	$dbconn = self::connect();
    	$sql = <<<SQL
		SELECT
			$what
		FROM
			$from
		WHERE
			$where
		ORDER BY
			$order_by
SQL;
		$result = $dbconn->query($sql);
		return $result;
    }  
    public static function insert($where,$values){
    	$dbconn = self::connect();
    	$sql = <<<SQL
    	INSERT INTO
    		$where
    	VALUES ($values)
SQL;
		$result = $dbconn->$query($sql);
    }  
    public static function delete() {
    	
    }  
    public static function update() {
    	
    }
}

?>