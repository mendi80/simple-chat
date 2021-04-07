<?php

if(!isset($_POST["up"])) {echo 0; return;}
unset( $_GET );

require 'autoloader.inc.php';

require_once('DB.php'); 

$errcounter = -1;
if(!isset($_POST["nickname"]) || !isset($_POST["secret"])) {echo $errcounter; return;} ;$errcounter--;
if(empty($_FILES)) {echo $errcounter; return;} ;$errcounter--;
if(empty($_FILES["files"])) {echo $errcounter; return;} ;$errcounter--;
//todo add in users: permitupload

$FILES_INDEX = "../files_index.txt";
$TARGET_DIR = "../main_files2021/";
$WEB_DIR = "http://sidersfile/"; // files aliasing to main_files2021
$MAX_FILES_PER_BATCH = 100; // php.ini: max_file_uploads=100
$MAX_BATCHES_PER_SECOND = 5; // actually per same period
$MUL_PREFIX = 100; assert($MUL_PREFIX>=$MAX_FILES_PER_BATCH);
$MAX_FILE_SIZE = 10*1024*1024; // sync with js upload_settings = {max_size:10*1024*1024}

$current_datetime_str = gmdate('ymdHis',time()+3*3600); 
$current_datetime_int = intval($current_datetime_str);

$is_multi = is_array($_FILES["files"]['name']);
$countfiles = $is_multi ? count($_FILES["files"]['name']) : 1;
if($countfiles>=$MAX_FILES_PER_BATCH) {echo $errcounter; return;} ;$errcounter--;


$nickname = $_POST["nickname"];
$secret = $_POST["secret"];
$user_id = $db->validUserByNickName($nickname,$secret);
if(!$user_id) {echo $errcounter; return;} ;$errcounter--;

// index files. look at last used index. prevent collision.
$collision_level_low = false;
$collision_level_high = false;
$final_datetime_int = $current_datetime_int;

if (!file_exists($FILES_INDEX)) {$f=fopen($FILES_INDEX, "w");fwrite($f, $current_datetime_int-2);fflush($f);fclose($f);}
$fid = fopen($FILES_INDEX, "r+");
if (flock($fid, LOCK_EX)) {
	$last_datetime_int =  intval(fgets($fid));
	$collision_level_low =  $current_datetime_int <= $last_datetime_int;
	$collision_level_high = $current_datetime_int <= $last_datetime_int - $MAX_BATCHES_PER_SECOND;
	$final_datetime_int = max($last_datetime_int+1, $current_datetime_int);
	if($collision_level_high==false){ 
		fseek($fid, 0);
		fwrite($fid, $final_datetime_int);
		fflush($fid);
	}
	// sleep(10);
	flock($fid, LOCK_UN);
}
fclose($fid);

assert($collision_level_high==false, "last_datetime_int > current_datetime_int, $last_datetime_int, $current_datetime_int");

//save uploaded files
$whitelist = explode("|", "gif|jpg|jpeg|png|webp|csv|pdf|md|doc|docx|xls"); //copy from httpd.conf under main_files2021
$nfiles_uploaded = 0;
$links = [];
for	($i = 0; $i < $countfiles; $i++)
{
	$file_size = $is_multi ? $_FILES["files"]["size"][$i] : $_FILES["files"]["size"];
	$file_name = $is_multi ? $_FILES["files"]["name"][$i] : $_FILES["files"]["name"];
	$tmp_name =  $is_multi ? $_FILES["files"]["tmp_name"][$i] : $_FILES["files"]["tmp_name"];
	$file_basename = basename($file_name);
	$file_extension = strtolower(pathinfo($file_basename,PATHINFO_EXTENSION)) ;
	$fileprefix_int = $final_datetime_int*$MUL_PREFIX + $i;
	$filename = strval($fileprefix_int).".".$file_extension;
	$target_path = $TARGET_DIR.$filename;
	$target_url = $WEB_DIR.$filename;
	if ($file_size > $MAX_FILE_SIZE || $file_size <= 0) continue;
	if (!in_array($file_extension, $whitelist)) continue;
	move_uploaded_file($tmp_name, $target_path);
	$db->addNewFile($user_id, $nickname, $fileprefix_int, $file_extension, $file_basename);
	array_push($links,$target_url);
	$nfiles_uploaded++;
}
$nfiles_not_uploaded = $countfiles - $nfiles_uploaded;

echo json_encode($links,JSON_UNESCAPED_SLASHES); // scripts ended with slash not applicable here
