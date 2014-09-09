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
	public $tab_names=array('start_tab','tracker_tab','precheck_tab','view_tab','summary_tab');
	public $tab_array;
	
	public function __construct(Logic $logic) {
		$this->my_logic=$logic;
	}
	
	public function populate_my_dom() {
		// Create the tabs and tables
		for($i=0;$i<count($this->tab_names);$i++) {
			$tab_array[$i]=new Tabs($i);
		}

		// Add tabs to the ui
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
}

class ButtonBar extends Presenter {
	public $bar_id;
	public $bar_location;
	public $bar_css_class;
	public $num_buttons;
	public $button_ids;
	public $button_values;
	public $this_bar;
	
	public function __construct($bar_id,$bar_location,$bar_css_class,$button_ids,$button_values,$num_buttons) {
		$this->set_bar_id($bar_id);
		$this->set_bar_location($bar_location);
		$this->set_bar_css_class($bar_css_class);
		$this->set_num_buttons($num_buttons);
		$this->set_button_ids($button_ids);
		$this->set_button_values($button_values);
		
		$this->this_bar=$this->build_button_bar();
		return $this->this_bar;
	}
	
	public function set_bar_id($bar_id) {
		$this->bar_id=$bar_id;
	}
	public function set_bar_location($bar_location) {
		$this->bar_location=$bar_location;
	}
	public function set_bar_css_class($bar_css_class) {
		$this->bar_css_class=$bar_css_class;
	}
	public function set_num_buttons($num_buttons) {
		$this->num_buttons=$num_buttons;
	}
	public function set_button_ids($button_ids) {
		$this->button_ids=$button_ids;
	}
	public function set_button_values($button_values) {
		$this->button_values=$button_values;
	}
	public function get_button_bar_string() {
		return $this->this_bar;
	}
	public function build_button_bar() {
		$bar_construct='';
		$bar_construct=$bar_construct."<td class='my_buttonbar'><div id='".$this->bar_id."' class='".$this->bar_css_class."'><span class='buttonset'>";
		for($i=0;$i<$this->num_buttons;$i++) {
			$bar_construct=$bar_construct."<span class='my_button'><button id='".$this->button_ids[$i]."'>".$this->button_values[$i]."</button></span>";
		}
		$bar_construct=$bar_construct."</span></div></td>";
		return $bar_construct;
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

class SelectMenu extends Presenter {
// A class that creates a select menu populated with data from the database
	public $label_value;
	public $select_id;
	public $options_array=array();
	
	public function __construct($label_value,$select_id,$options_array) {
		$this->set_label_value($label_value);
		$this->set_select_id($select_id);
		$this->set_options_array($options_array);
		
		$temp_menu=$this->build_select_menu();
		return $temp_menu;
	}
	
	public function set_label_value($label_value) {
		$this->label_value=$label_value;
	}
	
	public function set_select_id($select_id) {
		$this->select_id=$select_id;
	}
	
	public function set_options_array($options_array) {
		$this->options_array=$options_array;
	}
	
	public function build_select_menu() {
		$select_construct='';
		$select_construct=$select_construct.'<fieldset><label for="'.$this->select_id.'">'.$this->label_value.'</label>';
		$select_construct=$select_construct.'<select id="'.$this->select_id.'">';
		for($i=0;$i<count($this->options_array);$i++) {
			$select_construct=$select_construct.'<option value="'.$this->options_array[$i]['value'].'">'.$this->options_array[$i]['display'].'</option>';
		}
		$select_construct=$select_construct.'</select></fieldset>';
		return $select_construct;
	}
}

class Store extends SelectMenu {
	public $stores_array=array();
	public $options_array=array();
	
	public function _construct($label_value, $select_id) {
		$this->stores_array=$this->my_logic->get_stores();
		for($i=0;$i<count($this->stores_array);$i++) {
			foreach($this->stores_array[$i] as $key=>$pair) {
				$this->$options_array[$i]['value']=$this->stores_array[$i];
				$this->$options_array[$i]['display']=$this->stores_array[$i].' - '.$this->stores_array[$i]['tech_assigned'];
			}
		}
		return parent::__construct($label_value, $select_id, $this->$options_array);
	}
}

class Accordion extends Presenter {
	public $id;
	public $location;
	public $num_divs;
	public $class_array=array();
	public $hthree_array=array();
	public $div_handle_array=array();
	public $acc_string;
	
	public function __construct($id,$location,$num_divs,$class_array,$hthree_array,$div_handle_array) {
		$this->set_class_array($class_array);
		$this->set_div_handle_array($div_handle_array);
		$this->set_hthree_array($hthree_array);
		$this->set_id($id);
		$this->set_location($location);
		$this->set_num_divs($num_divs);
		
		$this->acc_string=$this->build_accordion();
		return $this->acc_string;
	}
	
	public function set_id($id) {
		$this->id=$id;
	}
	public function set_location($location) {
		$this->location=$location;
	}
	public function set_num_divs($num_divs) {
		$this->num_divs=$num_divs;
	}
	public function set_class_array($class_array) {
		$this->class_array=$class_array;
	}
	public function set_hthree_array($hthree_array) {
		$this->hthree_array=$hthree_array;
	}
	public function set_div_handle_array($div_handle_array) {
		$this->div_handle_array=$div_handle_array;
	}
	
	public function build_accordion() {
		$accordion_construct='';
		$accordion_construct=$accordion_construct.'<div id="'.$this->id.'">';
		for($i=0;$i<$this->num_divs;$i++) {
			$accordion_construct=$accordion_construct.'<div class="'.$this->class_array[$i].'"><h3>'.$this->hthree_array[$i].'</h3><div id="'.$this->div_handle_array[$i].'"></div></div></div>';
		}
		return $accordion_construct;
	}
}

class ActivationInformation extends Accordion {
	public $stores_array=array();
	public $user_store=array();
	public $temp_acc;
	public $temp_tables;
	
	public function __construct($id,$location,$num_divs,$class_array,$hthree_array,$div_handle_array) {
		$this->stores_array=$this->my_logic->get_stores();
		$this->get_user_store();
		$this->temp_acc=parent::__construct($id,$location,$num_divs,$class_array,$hthree_array,$div_handle_array);
		$this->populate_acc_data();
		return $this->temp_acc;
	}
	
	public function get_user_store() {
		for($i=0;$i<count($this->stores_array);$i++) {
			if($this->stores_array[$i]['tech_assigned']==$this->my_logic->username) {
				$temp_array=array(
					'store_number'=>$this->stores_array[$i],
					'scheduled'=>$this->stores_array[$i]['scheduled'],
					'tech_working'=>$this->stores_array[$i]['tech_working'],
					'tech_assigned'=>$this->stores_array[$i]['tech_assigned'],
					'type'=>$this->stores_array[$i]['type'],
					'primary_access'=>$this->stores_array[$i]['primary_access'],
					'backup_carrier'=>$this->stores_array[$i]['backup_carrier'],
					'eon'=>$this->stores_array[$i]['eon'],
					'ops_console'=>$this->stores_array[$i]['ops_console'],
					'bridge'=>$this->stores_array[$i]['bridge']);
				$this->user_store=$temp_array;
			}
		}
	}
	
	public function populate_acc_data() {
		
	}
}

class Tabs extends Presenter {
// A class that creates the tab structure and populates them with tables.
	public $SBAXML_obj;
	public $tab_prop=array();
	
	public function __construct($rti) {
		$this->SBAXML_obj=new SBAXML();
		//Store tab properties
		$this->tab_prop=$this->SBAXML_obj->get_tab_prop($rti);
	}
	
	public function set_tables() {
		$temp_obj;
		$holding_cell=array();
		$bbars=array();
		$tous=array();
		$insert;
		$tab_handle=$this->tab_prop['handle'];
		$div_wrapper_open="<div id='" . $tab_handle . "'>";
		$div_wrapper_close="</div>";
		// Add the tables to the holding cell
		for($i=0;$i<$this->tab_prop['tables'];$i++) {
			$tbl_handle=$this->tab_prop['table'.$i]['handle'];
			$tbl_rows=$this->tab_prop['table'.$i]['rows'];
			$tbl_cols=$this->tab_prop['table'.$i]['cols'];
			$tbl_divs=$this->tab_prop['table'.$i]['divs'];
			$temp_obj=new Table($tbl_cols,$tbl_rows,$tbl_divs,$tbl_handle);
			$holding_cell['table'.$i]=$temp_obj->build_table();
			// If there are button bars, add them to the holding cell in the right spot
			if($this->tab_prop['bbars']!='0') {
				for($y=0;$y<$this->tab_prop['bbars'];$y++) {
					if($this->tab_prop['table'.$i]['bar']=='true') {
						// Set temporary local variables to hold the button bar properties passed to the ButtonBar constructor
						$temp_id=$this->tab_prop['table'.$i]['button_bar'.$y]['handle'];
						$temp_location=$this->tab_prop['table'.$i]['button_bar'.$y]['location'];
						$temp_class=$this->tab_prop['table'.$i]['button_bar'.$y]['class'];
						$temp_num_buttons=$this->tab_prop['table'.$i]['button_bar'.$y]['buttons'];
						$temp_button_ids=array();
						$temp_button_values=array();
						for($z=0;$z<$temp_num_buttons;$z++) {
							$temp_button_ids[$z]=$this->tab_prop['table'.$i]['button_bar'.$y]['button'.$z]['handle'];
							$temp_button_values[$z]=$this->tab_prop['table'.$i]['button_bar'.$y]['button'.$z]['value'];
						}
						$bbar=new ButtonBar($temp_id,$temp_location,$temp_class,$temp_button_ids,$temp_button_values,$temp_num_buttons);
						// Insert new button bar string into the appropriate spot in the table string (In the right array slot, in the right div)
						$div_pos="<td><div id='".$temp_location."'></div></td>";
						$insert=$bbar->get_button_bar_string();
						$string=$holding_cell['table'.$i];
						$bbar_table=$this->SBAXML_obj->into_div($div_pos, $insert, $string);
						// Store the new table with the button bar
						$holding_cell['table'.$i]=$bbar_table;
					}
				}
			}
			// If there are static data divs, add them to the holding cell in the right spot
			if($this->tab_prop['num_divs']!='0') {
				for($y=0;$y<$this->tab_prop['num_divs'];$y++) {
					if($this->tab_prop['table'.$i]['has_div']=='true') {
						$temp_id=$this->tab_prop['table'.$i]['div'.$y]['handle'];
						$temp_data=$this->tab_prop['table'.$i]['div'.$y]['data'];
						$div_pos="<td><div id='".$temp_id."'></div></td>";
						$insert="<td><div id='".$temp_id."'><p>".$temp_data."</p></div></td>";
						$string=$holding_cell['table'.$i];
						$div_table=$this->SBAXML_obj->into_div($div_pos, $insert, $string);
						$holding_cell['table'.$i]=$div_table;
						
					}
				}
			}
			// If there are select menus, add them to the holding cell in the right spot
			//if($this->tab_prop['num_selectmenus']!='0') {
				//for($y=0;$y<$this->tab_prop['num_selectmenus'];$y++) {
					//if($this->tab_prop['table'.$i]['selectmenu'=='true']) {
						//$temp_id=$this->tab_prop['table'.$i]['select'.$y]['handle'];
						//$temp_label_value=$this->tab_prop['table'.$i]['select'.$y]['label_value'];
						//$temp_location=$this->tab_prop['table'.$i]['select'.$y]['location'];
						//$temp_obj=new Store($temp_label_value,$temp_id);
						
						//$store_select=$temp_obj->build_select_menu();
						//$div_pos="<td><div id='".$temp_location."'></div></td>";
						//$insert="<td><div id='".$temp_location."'>".$store_select."</div></td>";
						//$string=$holding_cell['table'.$i];
						//$select_table=$this->SBAXML_obj->into_div($div_pos,$insert,$string);
						//$holding_cell['table'.$i]=$select_table;
					//}
				//}
			//}
			// If there are accordions, add them to the holding cell in the right spot
			if($this->tab_prop['num_accordions']!='0') {
				for($y=0;$y<$this->tab_prop['num_accordions'];$y++) {
					if($this->tab_prop['table'.$i]['accordion']=='true') {
						$temp_id=$this->tab_prop['table'.$i]['accordion'.$y]['handle'];
						$temp_location=$this->tab_prop['table'.$i]['accordion'.$y]['location'];
						$temp_ac_divs=$this->tab_prop['table'.$i]['accordion'.$y]['ac_divs'];
						$temp_class=array();
						$temp_hthree=array();
						$temp_div_handle=array();
						$temp_tables=array();
						$temp_acc_str;
						for($x=0;$x<$temp_ac_divs;$x++) {
							$temp_class[$x]=$this->tab_prop['table'.$i]['accordion'.$y]['ac_div'.$x]['class'];
							$temp_hthree[$x]=$this->tab_prop['table'.$i]['accordion'.$y]['ac_div'.$x]['hthree'];
							$temp_div_handle[$x]=$this->tab_prop['table'.$i]['accordion'.$y]['ac_div'.$x]['div_handle'];
							for($z=0;$z<$this->tab_prop['table'.$i]['accordion'.$y]['ac_div'.$x]['num_tables'];$z++) {
								$temp_rows=$this->tab_prop['table'.$i]['accordion'.$y]['ac_div'.$x]['table'.$z]['rows'];
								$temp_cols=$this->tab_prop['table'.$i]['accordion'.$y]['ac_div'.$x]['table'.$z]['cols'];
								$temp_divs=$this->tab_prop['table'.$i]['accordion'.$y]['ac_div'.$x]['table'.$z]['divs'];
								$temp_thandle=$this->tab_prop['table'.$i]['accordion'.$y]['ac_div'.$x]['table'.$z]['handle'];
								$temp_tables[$z]=new Table($temp_cols,$temp_rows,$temp_divs,$temp_thandle);
							}
						}
						if($temp_id=="activation_information") {
							$temp_acc_str=new ActivationInformation($temp_id,$temp_location,$temp_ac_divs,$temp_class,$temp_hthree,$temp_div_handle);
						}
						else {
							$temp_acc_str=new Accordion($temp_id,$temp_location,$temp_ac_divs,$temp_class,$temp_hthree,$temp_div_handle);
						}
						$div_pos="<td><div id='".$temp_location."'></div></td>";
						$insert="<td><div id='".$temp_location."'>".$temp_acc_str."</div></td>";
						$string=$holding_cell['table'.$i];
						$acc_table=$this->SBAXML_obj->into_div($div_pos,$insert,$string);
						$holding_cell['table'.$i]=$acc_table;						
					}
				}
			}
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
	
	public function get_stores() {
		$temp_array=array();
		$what='*';
		$from='activation_information,activation_status,precheck';
		$where='activation_information.id_info=precheck.id_precheck AND activation_information.id_info=activation_status.id_status AND activation_status.tonight="1"';
		$order_by='activation_information.store_number';
		$result = Database::select($what,$from,$where,$order_by);
		foreach($result as $store){
			$temp_array[$store['store_number']]=array(
				'scheduled'=>$store['scheduled'],
				'tech_working'=>$store['tech_working'],
				'tech_assigned'=>$store['tech_assigned'],
				'type'=>$store['type'],
				'primary_access'=>$store['primary_access'],
				'backup_carrier'=>$store['backup_carrier'],
				'eon'=>$store['eon'],
				'ops_console'=>$store['ops_console'],
				'bridge'=>$store['bridge']);
		}
		return $temp_array;
	}
}

class SBAXML extends Logic{
// A class that creates an object that retrieves and manipulates sba_data.xml
	public $sba_xml;
	public function __construct() {
		$xml_path='http://localhost/wp-content/themes/hueman-child/sba_techtracker/PHP/sba_data.xml';
		$this->sba_xml=simplexml_load_file($xml_path);
		return $this->sba_xml;
	}
	
	public function get_tab_prop($rti) {
	//Pulls the requested tab data from the sba_xml doc

		$this_tab=array();	//Holds the tab the function will return

		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->attributes() as $key=>$pair) {
			$this_tab[$key]=$pair;
		}
		for($i=0;$i<$this->sba_xml->sba_tab_layout->sba_tab[$rti]['tables'];$i++) {
			foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->attributes() as $key=>$pair) {
				$this_tab['table'.$i][$key]=$pair;
				if($key=='bar' and $pair=='true') {
					for($x=0;$x<count($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->button_bar);$x++) {
						foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->button_bar[$x]->attributes() as $key=>$pair) {
							$this_tab['table'.$i]['button_bar'.$x][$key]=$pair;
						}
						for($y=0;$y<$this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->button_bar[$x]['buttons'];$y++) {
							foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->button_bar[$x]->button[$y]->attributes() as $key=>$pair) {
							$this_tab['table'.$i]['button_bar'.$x]['button'.$y][$key]=$pair;
							}
						}
					}
				}
				if($key=='has_div' and $pair=='true') {
					for($x=0;$x<$this->sba_xml->sba_tab_layout->sba_tab[$rti]['num_divs'];$x++) {
						foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->div[$x]->attributes() as $key=>$pair) {
							$this_tab['table'.$i]['div'.$x][$key]=$pair;
						}
					};
				}
				//if($key=='selectmenu' and $pair=='true') {
					//for($x=0;$x<$this->sba_xml->sba_tab_layout->sba_tab[$rti]['num_selectmenus'];$x++) {
						//foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->select[$x]->attributes() as $key=>$pair) {
							//$this_tab['table'.$i]['select'.$x][$key]=$pair;
						//}
					//}
				//}
				if($key=='accordion' and $pair=='true') {
					for($x=0;$x<$this->sba_xml->sba_tab_layout->sba_tab[$rti]['num_accordions'];$x++) {
						foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->accordion[$x]->attributes() as $key=>$pair) {
							$this_tab['table'.$i]['accordion'.$x][$key]=$pair;
							for($y=0;$y<$this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->accordion[$x]['ac_divs'];$y++) {
								foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->accordion[$x]->ac_div[$y]->attributes() as $key=>$pair) {
									$this_tab['table'.$i]['accordion'.$x]['ac_div'.$y][$key]=$pair;
								}
								for($z=0;$z<$this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->accordion[$x]->ac_div[$y]['num_tables'];$z++) {
									foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$i]->accordion[$x]->ac_div[$y]->table[$z]->attributes() as $key=>$pair) {
										$this_tab['table'.$i]['accordion'.$x]['ac_div'.$y]['table'.$z][$key]=$pair;
									}
								}
							}
						}
					}
				}
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