<?php

$cmd=$_POST['cmd'];

switch ($cmd) {
	case "add_numbers":
		$num1=$_POST['first_number'];
		$num2=$_POST['second_number'];
		$result = array("result" => $num1 + $num2);
		$output = json_encode($result);
	  break;
  }

echo $output;
