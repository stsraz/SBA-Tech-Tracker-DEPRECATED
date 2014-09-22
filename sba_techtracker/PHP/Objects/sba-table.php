<?php
	class Table extends Presenter {
	// A class that makes custom table objects
		public $tcols;
		public $trows;
		public $tdivs;
		public $thandle;
		public $table_string;
		
		public function __construct($id,$cols,$rows,$divs) {
			$this->set_cols($cols);
			$this->set_rows($rows);
			$this->set_divs($divs);
			$this->set_div_handle($div_handle);
			$this->table_string=$this->build_table();
		}
		public function get_table_string() {
			return $this->table_string;
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
?>