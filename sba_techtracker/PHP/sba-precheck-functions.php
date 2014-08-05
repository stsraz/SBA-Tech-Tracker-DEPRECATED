<?php

function sba_precheck_callback() {
    //Checks the source of the ajax call.
    check_ajax_referer( 'sba-security-string', 'security');

	$response = (new Router($presenter))->do_action();
	return $response;
}
add_action( 'wp_ajax_precheck_callback', 'sba_precheck_callback');

class Router {
	private $presenter;
	
	private function __construct(Presenter $presenter) {
		$this->presenter=$presenter;
		
	}
	public function do_action() {
	    if (!isset($_POST['type'])) {
	    	return;
		}
        $response = $this->$presenter->$_POST['type']();
		return $response;
		die();
	}
}

class Logic {
	private $username;
	
	private function __construct($username) {
		$this->username=$username;
	}

	public function get_store_list() {
		$result = Database::select("store_number,assigned_sa_tech", "activation,precheck", "activation_completed='0' AND activation.store_number=precheck.store_number_precheck", "assigned_sa_tech");
		foreach($result as $store) {
			echo "<option>" . $store['store_number'] . " - " . $store['assigned_sa_tech'] . "</option>";
		}
		die();
	}
	
}

class Presenter {
	private $sba_logic;
	
	private function __construct(Logic $logic) {
		$this->sba_logic=$logic;
	}
	
	public function add_tabs() {
		echo <<<TABS
		<div id="tabs">
			<ul>
				<li><a href="#start"><span>Start Page</span></a></li>
				<li><a href="#tracker"><span>Tech Tracker</span></a></li>
				<li><a href="#precheck"><span>Precheck</span></a></li>
				<li><a href="#view"><span>View Activation</span></a></li>
				<li><a href="#summary"><span>Activation Summary</span></a></li>
			</ul>
			<div id="start">
				
			</div>
			<div id="tracker">
			
			</div>
			<div id="precheck">
				
			</div>
			<div id="view">
			
			</div>
			<div id="summary">
			
			</div>
		</div>
TABS;
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

class Start extends Presenter {
	private function __construct(Logic $logic) {
		parent::construct($logic);
		parent::add_tabs();
		parent::add_store_list();
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