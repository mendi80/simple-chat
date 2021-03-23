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
		} catch(PDOException $e) {
			echo "databse connection failed: " . $e->getMessage();
		}
	}

	private function getSingleRowFromPosts($post_id){
		$sql = "SELECT * FROM posts WHERE post_id=? LIMIT 1;";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$post_id]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOC;
		return $data;
	}
	private function getRootPostID($post_id){
		$sql = "SELECT rpost_id FROM posts WHERE post_id=? LIMIT 1;";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$post_id]);
		$data = $stmt->fetchColumn();
		return $data;
	}

	public function isUserExist($somenickname) {
		$sql = "SELECT EXISTS(SELECT 1 FROM users WHERE nickname = ?);";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$somenickname]);
		$data = $stmt->fetchColumn();
		return $data!="0";
	}
	public function addNewUser($nickname, $secret) { 
		$sql = "INSERT into users (created, nickname, secret) VALUES (NOW(), ?, ?)";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$nickname, $secret]);
	}
	public function getUserID($somenickname) {
		$sql = "SELECT user_id FROM users WHERE nickname = ?;";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$somenickname]);
		$data = $stmt->fetchColumn();
		return $data;
	}
	
	public function createPost($ppost_id, $user_id, $nickname, $secret, $title, $content) {
		$rpost_id = $this->getRootPostID($ppost_id); //root same as root of parent
		$sql = "INSERT into posts (created, ppost_id, rpost_id, user_id, nickname, secret, title, content) values (NOW(), ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$ppost_id,$rpost_id,$user_id,$nickname,$secret,$title,$content]);
		return 1;
	}
	public function updatePost($post_id, $ppost_id, $nickname, $secret, $title, $content) {
		$post_row = $this->getSingleRowFromPosts($post_id);
		if($post_row["ppost_id"]==$ppost_id && $post_row["nickname"]==$nickname && $post_row["secret"]==$secret) {
			$sql = "UPDATE posts SET title = ?, content = ? WHERE post_id = ?";
			$stmt = $this->con->prepare($sql);
			$stmt->execute([$title,$content,$post_id]);
			return 1;
		} else return 0;
	}	
	//post_id ppost_id rpost_id user_id created nickname secret title content blockedit blockreply
	public function getForumTitles() {
		$sql = "SELECT post_id, created, nickname, title FROM posts;";
		$stmt = $this->con->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);//(PDO::FETCH_ASSOC);
		return $data;
	}
	public function getPost($post_id) { //post and replies
		$sql = "SELECT post_id, ppost_id, created, nickname, title, content FROM posts WHERE post_id = ? OR rpost_id = ? ORDER BY post_id;";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$post_id,$post_id]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data;
	}
	public function getPostSingle($post_id) {
		$sql = "SELECT post_id, created, nickname, title, content FROM posts WHERE post_id=?;";
		$stmt = $this->con->prepare($sql);
		$stmt->execute([$post_id]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
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


