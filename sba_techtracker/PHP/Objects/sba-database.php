<?php
abstract class Database {
// An abstract class that holds methods to communicate with the database.
	
	public static function connect() {
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