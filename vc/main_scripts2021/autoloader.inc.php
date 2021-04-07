<?php


function myAutoLoader($className){
	
	$basePath = __DIR__ . '/';

	switch ($className){
		case "Firebase\JWT\JWT":
			$classPath="jwt/JWT.php";
			break;
	}
	
	$fullPath = $basePath . $classPath;
	
	require_once  $fullPath;
}

spl_autoload_register('myAutoLoader');

