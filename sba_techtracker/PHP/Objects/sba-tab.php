<?php
	class Tabs extends Presenter {
		// Properties
		public $tab_string;
		public $tab_id;
		public $num_tables;
		public $logic_object;
		public $sbaxml_object;
		//Methods
		public function __construct($rti) {
			$this->sbaxml_object=new SBAXML;
			$this->logic_object=new Logic(wp_get_current_user()->user_login);
			$this->get_tab_properties($rti);
			$this->tab_string="<div id='" . $this->tab_id . "'>";
			$this->add_tables($rti);
			$this->add_accordions($rti);
			$this->add_bbars($rti);
			$this->add_table_divs($rti);
			//$this->add_select_menus($rti);
			$this->tab_string .= "</div>";
		}
		public function get_tab_properties($rti) {
			$tab_properties=$this->sbaxml_object->get_tab_properties($rti);
			$this->tab_id=$tab_properties['id'];
			$this->num_tables=$tab_properties['num_tables'];
		}
		public function add_tables($rti) {
			$tables_string='';
			for($i=0;$i<$this->num_tables;$i++) {
				$table_properties=$this->sbaxml_object->get_table_properties($rti,$i);
				$id=$table_properties['id'];
				$cols=$table_properties['cols'];
				$rows=$table_properties['rows'];
				$divs=$table_properties['divs'];
				$temp_table=new Table($id,$cols,$rows,$divs);
				$temp_table_string=$temp_table->get_table_string();
				$tables_string .= $temp_table_string;
			}
			$this->tab_string .= $tables_string;
		}
		public function add_accordions($rti) {
			for($i=0;$i<$this->num_tables;$i++) {
				$table_properties=$this->sbaxml_object->get_table_properties($rti,$i);
				if(isset($table_properties['num_accordions'])) {
					for($j=0;$j<$table_properties['num_accordions'];$j++) {
						$accordion_properties=$this->sbaxml_object->get_accordion_properties($rti,$i,$j);
						$id=$accordion_properties['id'];
						$location=$accordion_properties['location'];
						$num_divs=$accordion_properties['num_accordion_divs'];
						$class_array=array();
						$hthree_array=array();
						$div_id_array=array();
						$table_array=array();
						for($k=0;$k<$accordion_properties['num_accordion_divs'];$k++) {
							$class_array[$k]=$accordion_properties['accordion_div'.$k]['class'];
							$hthree_array[$k]=$accordion_properties['accordion_div'.$k]['hthree'];
							$div_id_array[$k]=$accordion_properties['accordion_div'.$k]['div_id'];
							for($l=0;$l<$accordion_properties['accordion_div'.$k]['num_tables'];$l++) {
								$at_div_id=$accordion_properties['accordion_div'.$k]['table'.$l]['id'];
								$cols=$accordion_properties['accordion_div'.$k]['table'.$l]['cols'];
								$rows=$accordion_properties['accordion_div'.$k]['table'.$l]['rows'];
								$divs=$accordion_properties['accordion_div'.$k]['table'.$l]['divs'];
								$temp_table=new Table($at_div_id,$cols,$rows,$divs);
								$temp_table_string=$temp_table->get_table_string();
								$table_array[$k] .= $temp_table_string;
							}
						}
						$temp_accordion_object=new Accordion($id,$location,$num_divs,$class_array,$hthree_array,$div_id_array,$table_array);
						$temp_accordion_string=$temp_accordion_object->get_accordion_string();
						$div_pos="<div id='" . $accordion_properties['location'] . "'></div>";
						$insert="<div id='" . $accordion_properties['location'] . "'>" . $temp_accordion_string . "</div>";
						$string=$this->tab_string;
						$accordion_to_tab_string=$this->logic_object->into_div($div_pos,$insert,$string);
						$this->tab_string=$accordion_to_tab_string;
					}
				}
			}
		}
		public function add_bbars($rti) {
			for($i=0;$i<$this->num_tables;$i++) {
				$table_properties=$this->sbaxml_object->get_table_properties($rti,$i);
				if(isset($table_properties['num_button_bars'])) {
					for($j=0;$j<$table_properties['num_button_bars'];$j++) {
						$button_bar_properties=$this->sbaxml_object->get_button_bar_properties($rti,$i,$j);
						$bar_id=$button_bar_properties['id'];
						$bar_location=$button_bar_properties['location'];
						$bar_css_class=$button_bar_properties['class'];
						$button_ids=$button_bar_properties['button_ids'];
						$button_values=$button_bar_properties['button_values'];
						$num_buttons=$button_bar_properties['num_buttons'];						
						$temp_button_bar_object=new ButtonBar($bar_id,$bar_location,$bar_css_class,$button_ids,$button_values,$num_buttons);
						$temp_button_bar_string=$temp_button_bar_object->get_button_bar_string();
						$div_pos="<div id='" . $bar_location . "'></div>";
						$insert="<div id='" . $bar_location . "'>" . $temp_button_bar_string . "</div>";
						$string=$this->tab_string;
						$button_bar_to_tab_string=$this->logic_object->into_div($div_pos,$insert,$string);
						$this->tab_string=$button_bar_to_tab_string;
					}
				}
			}
		}
		public function add_table_divs($rti) {
			for($i=0;$i<$this->num_tables;$i++) {
				$table_properties=$this->sbaxml_object->get_table_properties($rti,$i);
				if(isset($table_properties['num_table_divs'])) {
					for($j=0;$j<$table_properties['num_table_divs'];$j++) {
						$table_div_properties=$this->sbaxml_object->get_table_div_properties($rti,$i,$j);
						$location=$table_div_properties['location'];
						$data=$table_div_properties['data'];
						$div_pos="<div id='" . $location . "'></div>";
						$insert="<div id='" . $location . "'>" . $data . "</div>";
						$string=$this->tab_string;
						$table_div_to_tab_string=$this->logic_object->into_div($div_pos,$insert,$string);
						$this->tab_string=$table_div_to_tab_string;
					}
				}
			}
		}
		public function add_select_menus($rti) {
			
		}
		public function get_tab() {
			return $this->tab_string;
		}
	}






/*
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
								$temp_table_obj=new Table($temp_cols,$temp_rows,$temp_divs,$temp_thandle);
								$temp_tables[$z]=$temp_table_obj->build_table();
							}
						}
						
						$temp_acc_obj=new Accordion($temp_id,$temp_location,$temp_ac_divs,$temp_class,$temp_hthree,$temp_div_handle);
						$temp_acc_str=$temp_acc_obj->get_acc_string();
						
						if($temp_id=='activation_information') {
							for($i=0;$i<count($temp_tables);$i++) {
								$div_pos='<div id="' . $temp_div_handle[$i] . '"></div>';
								$insert=implode($temp_tables);
								$string=$temp_acc_str;
								$temp_acc_string= $this->my_logic->into_div($div_pos, $insert, $string);
							}
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
} */
?>