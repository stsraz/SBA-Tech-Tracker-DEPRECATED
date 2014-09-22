<?php
	class Accordion extends Presenter {
		
	}










/*
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
	}
	public function get_acc_string() {
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
			$accordion_construct=$accordion_construct.'<h3>'.$this->hthree_array[$i].'</h3><div class="'.$this->class_array[$i].'" id="'.$this->div_handle_array[$i].'"></div>';
		}
		$accordion_construct=$accordion_construct.'</div>';
		return $accordion_construct;
	}
}
*/
?>