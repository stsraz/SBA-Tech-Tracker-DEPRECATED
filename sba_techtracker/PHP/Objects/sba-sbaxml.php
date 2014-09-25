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
			}
		}
		return $accordion_properties;
	}
	public function get_table_div_properties($rti,$table_num,$table_div_num) {
		$table_div_properties=array();	// An array that will hold the requested button bar's properties
		foreach($this->sba_xml->sba_tab_layout->sba_tab[$rti]->table[$table_num]->table_div[$table_div_num]->attributes() as $key=>$pair) {
			$table_div_properties[$key]=$pair;
		}
		return $table_div_properties;
	}
}
	













	
/*
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
	}}*/
?>