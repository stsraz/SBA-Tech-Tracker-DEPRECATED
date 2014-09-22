<?php
	class SelectMenu extends Presenter {
		
	}











/*
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
*/
?>