<?php


if(!isset($_POST['op'])) {echo 0; return;}
unset( $_GET );

$t0 = microtime(true);

require 'autoloader.inc.php';
// https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/

require_once('DB.php'); 

// check if valid
$valid = true; 
//if(isset($_POST['msg_id'])) $valid = $valid && filter_var($msg_id, FILTER_VALIDATE_INT);
//if(isset($_POST['rpost_id'])) $valid = $valid && filter_var($rpost_id, FILTER_VALIDATE_INT);
//if(isset($_POST['n_replies'])) $valid = $valid && filter_var($n_replies, FILTER_VALIDATE_INT);
//if(isset($_POST['n_edits'])) $valid = $valid && filter_var($n_edits, FILTER_VALIDATE_INT);
//if(isset($_POST['nickname'])) $valid = $valid && filter_var($n_edits, FILTER_VALIDATE_INT);
//if (preg_match('/^[A-Za-z0-9_-]*$/', $yourString)) {}

//echo json_encode([array('first_name' => 'mendi')]);

$op=$_POST['op'];

switch ($op) {
	case 'identify':
		
	case 'fetch':
		if(isset($_POST['msg_id'])){
			$msg_id = $_POST['msg_id'];
			$rpost_id0 = $_POST['rpost_id'];
			$n_replies = $_POST['n_replies'];
			$n_edits = $_POST['n_edits'];
		} else { 
			$msg_id = $rpost_id0 = $n_replies= $n_edits= $n_edits=0;
		}
		$rpost_id = $db->getRootPostID($rpost_id0);
		$post_state = $db->getSingleRowFromThreads($rpost_id);
		
		if($post_state && ($n_replies!=$post_state['n_replies'] || $n_edits!=$post_state['n_edits'])) {
			if($rpost_id==0)	
				$data = $db->getForumTitles();
			 else 
				$data = $db->getPost($rpost_id);
			$header = array('msg_id' => $msg_id, 'rpost_id' => $rpost_id, 'n_replies' => $post_state['n_replies'], 'n_edits' => $post_state['n_edits']);
			$script = " handleUser.__proto__.eraseStorageBBB = function(){
				console.log(this.elem_nickname.value);
				debugger;
				localStorage.setItem('usernickname','doe');
				localStorage.setItem('usercipher','123');
				this.loadFromStorage();
				this.showInputs();
				console.log(this.elem_nickname.value);
				}";
			$script = "console.log('dynamic script')";
			$output = json_encode([$header,$data,$script],JSON_PRETTY_PRINT);
		}
		else 
			$output = $msg_id; //same

	  break; 
	case 'getpost':
		$post_id = $_POST['post_id']; // var_dump($_POST);
		$data = $db->getPost($post_id); // var_dump($data);
		$output = json_encode($data,JSON_PRETTY_PRINT);
	  break;
	//post_id ppost_id tmprpost_id rpost_id user_id created nickname secret title content blockedit blockreply
	case 'setpost':
		$post_id = trim($_POST['post_id']); 
		$ppost_id = trim($_POST['ppost_id']); //parent post id
		$nickname = filter_var($_POST['nickname'] , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$secret = $_POST['secret']; 
		$title = $_POST['title']; 
		$content = $_POST['content']; 	//$rowexist = $db->isexist($title);	//var_dump($rowexist);
		if ( strlen($post_id)==0 || strlen($ppost_id)==0 || strlen($nickname)==0 || strlen($secret)==0 || strlen($title)==0|| strlen($content)==0 ) {
			$output = 0;
		} else {
			if (!$db->isUserExist($nickname))
				$db->addNewUser($nickname, $secret);
			$user_id = $db->getUserID($nickname);
			
			if ($post_id>0 && $ppost_id==$post_id) 
				$output = $db->updatePost($post_id, $user_id, $nickname, $secret, $title, $content); //edit
			else 
				$output = $db->createPost($ppost_id, $user_id, $nickname, $secret, $title, $content); //new post

		}
	  break;
	case 'searchname':
		$name= $_POST['searchname']; 
		$data = $db->searchData($name);
		$output = json_encode($data);
		break;

	case 'create_random_table':
		$nickname = filter_var($_POST['nickname'] , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$secret = $_POST['secret'];
		$nrows = $_POST['nrows'];
		$output = "created=";
		if ($nickname=="mendi80" && $db->validUserByNickName($nickname)) {
			$user_id = $db->getUserID($nickname);
			$last_post_id = $db->getLastPostID();
			$count = 0;
			for ($i = 0; $i < 10*$nrows; $i++) {
				$MAXPOSTID = $last_post_id+$i;
				$ppost_id=(rand(1,3)==1) ? 0 : rand(0,$MAXPOSTID);
				$title="This title is child of $ppost_id";
				$content="This content is child of $ppost_id";
				$count += $db->createPost($ppost_id, $user_id, $nickname, $secret, $title, $content);
			}
			$output .= $count.", ";
		}
		$t1 = microtime(true);
		$runtime_ms = round(1000*($t1 - $t0));
		$output .= "$runtime_ms ms"; 
		break;

	default: 
		$output = -1; 
  }

$t1 = microtime(true);
$runtime_ms = 1000*($t1 - $t0);
// $data['runtime'] = $runtime_ms;

echo $output;
