<?php

require_once('DB.php'); 
$name= $_POST['searchname']; //var_dump($_POST);
$data = $db->searchData($name); //var_dump($data);
echo json_encode($data);

//echo json_encode([array("first_name" => "mendi")]);
