<?php

require_once('DB.php'); 
$clientUpdatedTime = $_POST['clientUpdatedTime']; //var_dump($_POST);
$serverUpdatedTime = file_get_contents('tmp.txt');
if ($serverUpdatedTime == $clientUpdatedTime)
    echo 0;
else
{
    $data = $db->getForumTitles(); //var_dump($data);
    array_push($data, array("serverUpdatedTime" => $serverUpdatedTime));
    echo json_encode($data);
}

//echo json_encode([array("first_name" => "mendi")]);
