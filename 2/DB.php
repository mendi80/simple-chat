<?php

class DB {
	private $con;
	private const host = '127.0.0.1';
	private const dbname = 'livesearch';
	private const user = 'root';
	private const password = '';
	
	public function __construct() {
		$dsn = "mysql:host=" . self::host . ";dbname=" . self::dbname . ";" ;
		try {
			$this->con = new PDO($dsn, self::user, self::password);
			$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "database connection succedded\n";
		} catch(PDOException $e) {
			echo "databse connection failed: " . $e->getMessage();
		}
	}
	
	public function isexist($somename) {
		$sql = "SELECT EXISTS(SELECT 1 FROM author_names WHERE first_name = ?);";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$somename]);
		$data = $stmt->fetchColumn();//(PDO::FETCH_ASSOC);
		return $data!="0";
	}
	
	public function addNewRow($newname) {
		$sql = "insert into author_names (first_name) values (?)";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$newname]);
		//var_dump($stmt);
	}
	
	public function viewAllDatabase() {
		$sql = "SELECT first_name FROM author_names";
		$stmt = $this->con->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	
	public function searchData($somename) {
		$sql = "SELECT first_name FROM author_names WHERE first_name LIKE ?";
		$stmt = $this->con->prepare($sql);
		$stmt->execute(["%".$somename."%"]);
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	

	
}
$db = new DB();
//echo "DB object created\n";
// var_dump($db->searchData("y"))


