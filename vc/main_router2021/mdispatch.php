<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

//var_dump($_SERVER);

$n = 0;
if (isset($_POST["op"])) $n = 1; 
elseif (isset($_POST["up"])) $n = 2;

if ($n==1 || $n==2)
{
	include('../main_select2021/select.php');
} else {echo 0; return;}
