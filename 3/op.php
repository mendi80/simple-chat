<?php


$t0 = microtime(true);
//echo json_encode([array("first_name" => "mendi")]);

require_once('DB.php'); 


$op=$_POST['op'];
switch ($op) {
	case "gettitles":
		$clientUpdatedTime = $_POST['clientUpdatedTime'];
		$serverUpdatedTime = file_get_contents('tmp.txt');
		if ($serverUpdatedTime == $clientUpdatedTime) {
			$output = 0;
		}  else  {
			$data = $db->getForumTitles();
			array_push($data, array("serverUpdatedTime" => $serverUpdatedTime));
			$output = json_encode($data,JSON_PRETTY_PRINT);
		}
	  break;
	case "getpost":
		$post_id = $_POST['post_id']; // var_dump($_POST);
		$data = $db->getPost($post_id); // var_dump($data);
		$output = json_encode($data,JSON_PRETTY_PRINT);
	  break;
	case "setpost":
		$post_id = trim($_POST['post_id']); 
		$nickname = trim($_POST['nickname']); 
		$pwd = $_POST['secret']; 
		$title = $_POST['title']; 
		$content = $_POST['content']; 	//$rowexist = $db->isexist($title);	//var_dump($rowexist);
		if ( strlen($nickname)==0 || strlen($pwd)==0 || strlen($title)==0|| strlen($content)==0 ) {
			$output = 0;
		} else {
			if (!$db->isUserExist($nickname))
				$db->addNewUser($nickname, $pwd);
			$user_id = $db->getUserID($nickname);
			if ($post_id>0)
				$db->replacePost($post_id, $title, $content);
			else
				$db->addNewPost($user_id, $nickname, $title, $content);
			$lastchanged_time=time();
			file_put_contents( 'tmp.txt', $lastchanged_time);
			$output = 1;
		}
	  break;
	case "searchname":
		$name= $_POST['searchname']; 
		$data = $db->searchData($name);
		$output = json_encode($data);
		break;
	default: $output = -1; break;
  }

$t1 = microtime(true);
$runtime_ms = 1000*($t1 - $t0);
// $data["runtime"] = $runtime_ms;

echo $output;
