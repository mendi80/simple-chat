<?php
require_once('DB.php');

//var_dump($_POST);


$nickname = trim($_POST['nickname']); 
$pwd = $_POST['pwd']; 
$title = $_POST['title']; 
$content = $_POST['content']; 

//$rowexist = $db->isexist($title);
//var_dump($rowexist);

if ( strlen($nickname)==0 || strlen($pwd)==0 || strlen($title)==0|| strlen($content)==0 )
	echo 0;
else
{
	if (!$db->isUserExist($nickname))
		$db->addNewUser($nickname, $pwd);
	$user_id = $db->getUserID($nickname);
	$db->addNewPost($user_id, $nickname, $title, $content);
	$lastchanged_time=time();
	file_put_contents( 'tmp.txt', $lastchanged_time);
	echo 1;
}

