<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

//var_dump($_SERVER);

$n = 0;
if (isset($_POST["op"])) $n = 1; 
elseif (isset($_POST["up"])) $n = 2;


if(count($_GET)>0 && isset($_GET["a"]))
{
	switch ($_GET["a"]){
		case "jwt": include('../main_scripts2021/test_jwt.php'); break;
		case "for": include('../main_scripts2021/bench_php.php'); break;
	}
	$n=4;
}


if($n==1 || $n==2) include('../main_select2021/select.php');
if($n==0) echo 0; 

