<?php

function curdate() {
    // gets current timestamp
    date_default_timezone_set('Asia/Manila'); // What timezone you want to be the default value for your current date.
    return date('Y-m-d H:i:s');
}

class DB {
	private $con;
	private const host = '127.0.0.1';
	private const dbname = 'mendi_forum_01';
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

	public function isUserExist($somenickname) {
		$sql = "SELECT EXISTS(SELECT 1 FROM users WHERE nickname = ?);";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$somenickname]);
		$data = $stmt->fetchColumn();//(PDO::FETCH_ASSOC);
		return $data!="0";
	}

	public function addNewUser($nickname, $pwd) { 
		$sql = "INSERT into users (created_datetime, nickname, pwd) VALUES (NOW(), ?, ?)";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$nickname, $pwd]);
		//var_dump($stmt);
	}

	public function getUserID($somenickname) {
		$sql = "SELECT user_id FROM users WHERE nickname = ?;";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$somenickname]);
		$data = $stmt->fetchColumn();//(PDO::FETCH_ASSOC);
		return $data;
	}
	
	public function addNewPost($user_id, $nickname, $title, $content) {
		$sql = "INSERT into posts (created_datetime, user_id, nickname, title, content) values (NOW(), ?, ?, ?, ?)";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$user_id,$nickname,$title,$content]);
	}	
	
	public function getForumTitles() {
		$sql = "SELECT created_datetime, nickname, title FROM posts;";
		$stmt = $this->con->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);//(PDO::FETCH_ASSOC);
		return $data;
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


