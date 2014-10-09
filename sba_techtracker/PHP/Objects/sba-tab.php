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
			$this->add_select_menus($rti);
			$this->tab_string .= "</div>";
		}
		public function get_tab_properties($rti) {
			$tab_properties=$this->sbaxml_object->get_tab_properties($rti);
			$this->tab_id=$tab_properties['id'];
			$this->num_tables=$tab_properties['num_tables'];
		}
		public function get_tab_string() {
			return $this->tab_string;
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
							$table_array[$k]='';
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
				if(isset($table_properties['num_accordions'])) {
					for($j=0;$j<$table_properties['num_accordions'];$j++) {
						$accordion_table_div_properties=$this->sbaxml_object->get_accordion_table_div_properties($rti,$i,$j);
						for($k=0;$k<$accordion_table_div_properties['num_accordion_divs'];$k++) {
							for($l=0;$l<$accordion_table_div_properties['accordion_div'.$k]['num_tables'];$l++) {
								for($m=0;$m<$accordion_table_div_properties['accordion_div'.$k]['table'.$l]['num_table_divs'];$m++) {
									$location=$accordion_table_div_properties['accordion_div'.$k]['table'.$l]['table_div'.$m]['location'];
									$data=$accordion_table_div_properties['accordion_div'.$k]['table'.$l]['table_div'.$m]['data'];
									$div_pos="<div id='" . $location . "'></div>";
									$insert="<div id='" . $location . "'>" . $data . "</div>";
									$string=$this->tab_string;
									$accordion_div_to_tab_string=$this->logic_object->into_div($div_pos,$insert,$string);
									$this->tab_string=$accordion_div_to_tab_string;
								}
							}
						}
					}
				}
			}
		}
		public function add_select_menus($rti) {
			for($i=0;$i<$this->num_tables;$i++) {
				$table_properties=$this->sbaxml_object->get_table_properties($rti,$i);
				if(isset($table_properties['num_select_menus'])) {
					for($j=0;$j<$table_properties['num_select_menus'];$j++) {
						$select_menu_properties=$this->sbaxml_object->get_select_menu_properties($rti,$i,$j);
						$select_id=$select_menu_properties['id'];
						$options_array=$this->logic_object->get_upcoming_stores_list();
						$select_location=$select_menu_properties['location'];
						$label_value=$select_menu_properties['label_value'];
						$temp_select_menu_object=new SelectMenu($label_value,$select_id,$options_array);
						$temp_select_menu_string=$temp_select_menu_object->get_select_menu_string();
						$div_pos="<div id='" . $select_location . "'></div>";
						$insert="<div id='" . $select_location . "'>" . $temp_select_menu_string . "</div>";
						$string=$this->tab_string;
						$select_menu_to_tab_string=$this->logic_object->into_div($div_pos,$insert,$string);
						$this->tab_string=$select_menu_to_tab_string;
					}
				}
			}
		}
	}
?>