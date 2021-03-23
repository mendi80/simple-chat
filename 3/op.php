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
	//post_id ppost_id tmprpost_id rpost_id user_id created nickname secret title content blockedit blockreply
	case "setpost":
		$post_id = trim($_POST['post_id']); 
		$ppost_id = trim($_POST['ppost_id']); //parent post id
		$nickname = trim($_POST['nickname']); 
		$secret = $_POST['secret']; 
		$title = $_POST['title']; 
		$content = $_POST['content']; 	//$rowexist = $db->isexist($title);	//var_dump($rowexist);
		if ( strlen($nickname)==0 || strlen($secret)==0 || strlen($title)==0|| strlen($content)==0 ) {
			$output = 0;
		} else {
			if (!$db->isUserExist($nickname))
				$db->addNewUser($nickname, $secret);
			$user_id = $db->getUserID($nickname);
			if ($post_id>0)
				$output = $db->updatePost($post_id, $ppost_id, $nickname, $secret, $title, $content);
			else
				$output = $db->createPost($ppost_id, $user_id, $nickname, $secret, $title, $content);
			$lastchanged_time=time();
			file_put_contents( 'tmp.txt', $lastchanged_time);
		}
	  break;
	case "searchname":
		$name= $_POST['searchname']; 
		$data = $db->searchData($name);
		$output = json_encode($data);
		break;

	case "create_random_table":
		$nrows = $_POST['nrows'];
		$last_post_id = $db->getLastPostID();
		for ($i = 0; $i < $nrows; $i++) {
			$MAXPOSTID = $last_post_id+$i;
			$ppost_id=(rand(1,100)==1) ? 0 : rand(0,$MAXPOSTID);
			$user_id=3;	$nickname="mendi80"; $secret="abc";
			$title="This title is child of $ppost_id";
			$content="This content is child of $ppost_id";
			$db->createPost($ppost_id, $user_id, $nickname, $secret, $title, $content);
		  }
		$lastchanged_time=time();
		file_put_contents( 'tmp.txt', $lastchanged_time);
		$t1 = microtime(true);
		$runtime_ms = round(1000*($t1 - $t0));
		$output = "$runtime_ms ms";
		break;

	default: 
		$output = -1; 
  }

$t1 = microtime(true);
$runtime_ms = 1000*($t1 - $t0);
// $data["runtime"] = $runtime_ms;

echo $output;
