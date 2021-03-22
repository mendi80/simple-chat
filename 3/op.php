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
			echo 0;
			return;
		}  else  {
			$data = $db->getForumTitles();
			array_push($data, array("serverUpdatedTime" => $serverUpdatedTime));
			echo json_encode($data,JSON_PRETTY_PRINT);
		}
	  break;
	case "getpost":
		$post_id = $_POST['post_id']; // var_dump($_POST);
		$data = $db->getPost($post_id); // var_dump($data);
	  break;
	case "setpost":
	  break;
	default: break;
  }


$t1 = microtime(true);
$data["runtime"] = 1000*($t1 - $t0);
echo json_encode($data,JSON_PRETTY_PRINT);



