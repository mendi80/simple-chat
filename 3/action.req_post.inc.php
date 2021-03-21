<?php

require_once('DB.php'); 
$t0 = microtime(true);
$post_id = $_POST['post_id']; //var_dump($_POST);
$data = $db->getPost($post_id); //var_dump($data);
$t1 = microtime(true);
$data["runtime"] = 1000*($t1 - $t0);
echo json_encode($data,JSON_PRETTY_PRINT);

//echo json_encode([array("first_name" => "mendi")]);
