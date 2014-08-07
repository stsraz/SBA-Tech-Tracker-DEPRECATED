<?php

function sba_precheck_callback() {
    //Checks the source of the ajax call.
    check_ajax_referer( 'sba-security-string', 'security');
	
	$action=$_POST['type'];
	$tab=$_POST['tab'];
	$username=wp_get_current_user()->user_login;
	$logic=new Logic($username);
	$presenter=new Presenter($logic);
	$router=new Router($presenter);
	$response=$router->do_action($action,$tab);
	echo $response;
}
add_action( 'wp_ajax_precheck_callback', 'sba_precheck_callback');

class Router {
	private $presenter;
	
	public function __construct(Presenter $presenter) {
		$this->presenter=$presenter;
		
	}
	public function do_action($action,$tab) {
        $response = $this->presenter->$action($tab);
		return $response;
	}
}

class Logic {
	private $username;
	
	public function __construct($username) {
		$this->username=$username;
	}

	public function get_store_list() {
		$result = Database::select("store_number,assigned_sa_tech", "activation,precheck", "activation_completed='0' AND activation.store_number=precheck.store_number_precheck", "assigned_sa_tech");
		foreach($result as $store) {
			echo "<option>" . $store['store_number'] . " - " . $store['assigned_sa_tech'] . "</option>";
		}
	}
	
}

class Presenter {
	private $my_logic;
	public $my_ui;
	public $start_div;
	
	public function __construct(Logic $logic) {
		$this->my_logic=$logic;
		$this->set_ui();
	}
	
	public function populate($tab) {
		$table=new Table(4,4,16,$tab);
		$table_obj=$table->build_table();
		$table_pos='<div id="'.$tab.'"></div>';
		$placeholder = $this->into_div($this->my_ui, $table_obj, $table_pos);
		$this->my_ui=$placeholder;
		return $this->my_ui;
	}
	
	public function set_ui() {
		$this->my_ui = '<div id="tabs"><ul><li><a href="#start"><span>Start Page</span></a></li><li><a href="#tracker"><span>Tech Tracker</span></a></li><li><a href="#precheck"><span>Precheck</span></a></li><li><a href="#view"><span>View Activation</span></a></li><li><a href="#summary"><span>Activation Summary</span></a></li></ul><div id="start"></div><div id="tracker"></div><div id="precheck"></div><div id="view"></div><div id="summary"></div></div>';
	}

	private function into_div($string,$insert,$div_position) {
		$new_string=str_replace($div_position,$insert,$string);
		return $new_string;
	}

	public function add_store_list() {
		echo <<<STORELIST
		<form>
			<label for="store">Store Number: </label>
			<select name="store" id="store">
				<option></option>
STORELIST;
		$this->sba_logic->get_store_list();
		echo "</select></form>";
	}
}

class Table extends Presenter {
	private $tcols;
	private $trows;
	private $tdivs;
	private $thandle;
	public $table='';
	
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
		$table_construct=$table_construct .  '<div id="start"><table style="border:1px solid black;" id="' . $this->thandle . '_table">';
		for($i=0;$i<$this->trows;$i++) {
			$table_construct=$table_construct .  "<tr>";
				for($z=0;$z<$this->tcols;$z++) {
					$table_construct=$table_construct .  "<td>";
						if($div_counter<$this->tdivs) {
							$table_construct=$table_construct .  "<div id='" . $this->thandle . "_" . $div_counter . "'></div>";
							$div_counter++;
						}
					$table_construct=$table_construct .  "</td>";
				}
			$table_construct=$table_construct .  "</tr>";
		}
		$table_construct=$table_construct .  "</table></div>";
		return $table_construct;
	}
}

abstract class Database {
	
	private static function connect() {
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