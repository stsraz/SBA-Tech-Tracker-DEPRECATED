<?php
	class ButtonBar extends Presenter {
	// A class that makes custom table objects
		public $bar_id;
		public $bar_location;
		public $bar_css_class;
		public $num_buttons;
		public $button_ids;
		public $button_values;
		public $button_bar_string;
		
		public function __construct($bar_id,$bar_location,$bar_css_class,$button_ids,$button_values,$num_buttons) {
			$this->set_bar_id($bar_id);
			$this->set_bar_location($bar_location);
			$this->set_bar_css_class($bar_css_class);
			$this->set_num_buttons($num_buttons);
			$this->set_button_ids($button_ids);
			$this->set_button_values($button_values);
			
			$this->button_bar_string=$this->build_button_bar();
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
			return $this->button_bar_string;
		}
		public function build_button_bar() {
			$bar_construct='';
			$bar_construct=$bar_construct."<div id='".$this->bar_id."' class='".$this->bar_css_class."'><span class='buttonset'>";
			for($i=0;$i<$this->num_buttons;$i++) {
				$bar_construct=$bar_construct."<span class='my_button'><button id='".$this->button_ids[$i]['id']."'>".$this->button_values[$i]['value']."</button></span>";
			}
			$bar_construct=$bar_construct."</span></div>";
			return $bar_construct;
		}
	}
?>