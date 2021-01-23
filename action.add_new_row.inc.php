<?php
require_once('DB.php');

//var_dump($_POST);

$newrow = $_POST['newrow']; 
$rowexist = $db->isexist($newrow);
//var_dump($rowexist);

if ( strlen($newrow)==0 || $rowexist )
	echo 0;
else{
	$db->addNewRow($newrow);
	echo 1;
}


