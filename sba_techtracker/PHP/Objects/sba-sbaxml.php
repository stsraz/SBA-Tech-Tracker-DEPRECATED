<?php
class SBAXML extends Logic{
// A class to create an object that retrieves and manipulates the static data held in sba_data.xml
	public $sba_xml;
	
	public function __construct() {
		$xml_path='http://localhost/wp-content/themes/hueman-child/sba_techtracker/sba-data.xml';
		$this->sba_xml=simplexml_load_file($xml_path);
	}
	public function get_tab_properties($rti) {
	//Retrieves the requested tab's properties from the xml doc
		$tab_properties=array();	// An array that will hold the requested tab's properties
		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->attributes() as $key=>$pair) {
			$tab_properties[$key]=$pair;
		}
		return $tab_properties;
	}
	public function get_table_properties($rti,$table_num) {
		$table_properties=array();	// An array that will hold the requested table's properties
		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->attributes() as $key=>$pair) {
			$table_properties[$key]=$pair;
		}
		return $table_properties;
	}
	public function get_button_bar_properties($rti,$table_num,$button_bar_num) {
		$button_bar_properties=array();	// An array that will hold the requested button bar's properties
		$temp_ids=array();		// An array that will temporarily hold the button ids
		$temp_values=array();	// An array that will temporarily hold the button values
		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->button_bar[$button_bar_num]->attributes() as $key=>$pair) {
			$button_bar_properties[$key]=$pair;
		}
		for($i=0;$i<$button_bar_properties['num_buttons'];$i++) {
			foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->button_bar[$button_bar_num]->button[$i]->attributes() as $key=>$pair) {
				if($key=="id") {
					$temp_ids[$i][$key]=$pair;
				}
				if($key=="value") {
					$temp_values[$i][$key]=$pair;
				}
			}
		}
		$button_bar_properties['button_ids']=$temp_ids;
		$button_bar_properties['button_values']=$temp_values;
		return $button_bar_properties;
	}
	public function get_select_menu_properties($rti,$table_num,$select_menu_num) {
		$select_menu_properties=array();	// An array that will hold the requested select menu's properties
		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->select_menu[$select_menu_num]->attributes() as $key=>$pair) {
			$select_menu_properties[$key]=$pair;
		}
		return $select_menu_properties;
		
	}
	public function get_accordion_properties($rti,$table_num,$accordion_num) {
		$accordion_properties=array();	// An array that will hold the requested accordions's properties
		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->attributes() as $key=>$pair) {
			$accordion_properties[$key]=$pair;
		}
		for($i=0;$i<$accordion_properties['num_accordion_divs'];$i++) {
			foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->accordion_div[$i]->attributes() as $key=>$pair) {
				$accordion_properties['accordion_div'.$i][$key]=$pair;
			}
			for($j=0;$j<$accordion_properties['accordion_div'.$i]['num_tables'];$j++) {
				foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->accordion_div[$i]->table[$j]->attributes() as $key=>$pair) {
					$accordion_properties['accordion_div'.$i]['table'.$j][$key]=$pair;
				}
				for($k=0;$k<count($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->accordion_div[$i]->table[$j]->table_div);$k++) {
					foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->accordion_div[$i]->table[$j]->table_div[$k]->attributes() as $key=>$pair) {
						$accordion_properties['accordion_div'.$i]['table'.$j]['table_div'.$k][$key]=$pair;
					}
				}
			}
		}
		return $accordion_properties;
	}
	public function get_table_div_properties($rti,$table_num,$table_div_num) {
		$table_div_properties=array();	// An array that will hold the requested table div's properties
		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->table_div[$table_div_num]->attributes() as $key=>$pair) {
			$table_div_properties[$key]=$pair;
		}
		return $table_div_properties;
	}
	public function get_accordion_table_div_properties($rti,$table_num,$accordion_num) {
		$accordion_table_div_properties=array(); // An array that will hold the requested accordion table div's properties
		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->attributes() as $key=>$pair) {
			if($key=='num_accordion_divs') {
				$accordion_table_div_properties[$key]=$pair;
			}
		}
		for($i=0;$i<$accordion_table_div_properties['num_accordion_divs'];$i++) {
			foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->accordion_div[$i]->attributes() as $key=>$pair) {
				if($key=='num_tables') {
					$accordion_table_div_properties['accordion_div'.$i][$key]=$pair;
				}
			}
			for($j=0;$j<$accordion_table_div_properties['accordion_div'.$i]['num_tables'];$j++) {
				foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->accordion_div[$i]->table[$j]->attributes() as $key=>$pair) {
					if($key=='num_table_divs') {
						$accordion_table_div_properties['accordion_div'.$i]['table'.$j][$key]=$pair;
					}
				}
				for($k=0;$k<$accordion_table_div_properties['accordion_div'.$i]['table'.$j]['num_table_divs'];$k++) {
					foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->accordion[$accordion_num]->accordion_div[$i]->table[$j]->table_div[$k]->attributes() as $key=>$pair) {
						$accordion_table_div_properties['accordion_div'.$i]['table'.$j]['table_div'.$k][$key]=$pair;
					}
				}
			}
		}
		return $accordion_table_div_properties;
	}
	public function get_pin($bridge) {
		$temp_array=array();
		$temp_pin;
		for($i=0;$i<count($this->sba_xml->bridge->pin);$i++) {
			foreach($this->sba_xml->bridge->pin[$i]->attributes() as $key=>$pair) {
				$temp_array[$i][$key]=$pair;
			}
		}
		for($i=0;$i<count($temp_array);$i++) {
			if($temp_array[$i]['code']==$bridge) {
				$temp_pin=(int)$temp_array[$i]['pin'];
				break;
			}
		}
		return $temp_pin;
	}
}
?>