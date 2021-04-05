
*todo
	dont convert small images. make clipboard file process as drag. give it a name and send it to handle file.
	try to compress raw png.


*ab
	ab -c 4 -n 2000 http://46.117.188.240:60111/mdispatch.php

*ip
	http://myexternalip.com/raw
	
*security steps:
	https://www.php.net/manual/en/security.hiding.php
	setting expose_php=off in your php.ini
	.htaccess in its files: php_flag engine off
	add .php filter in .htaccess
	disable browsing: httpd.conf
	ddos: apache2.conf: RequestReadTimeout header=10-20,MinRate=500 body=20,MinRate=500
	httpd.conf: disable mod_autoindex mod_dir cgi etc


*git multiple github accounts
	1) Create ssh KEY. repeat for each github account
	(delete git credendials from windows account)
	ssh-keygen -t rsa -f somefilenamecommi
	copy .pub file to clipboard and paste in github>settings>ssh>add ssh
	create/append in ~/.ssh/config
	2) config file example:
	HostName github.com
	Host siders
	IdentityFile C:/Users/Mendi/.ssh/vcoronacoil
	# git clone git@siders:vcorona-israel/website.git
	# git remote set-url origin git@siders:vcorona-israel/website.git

	HostName github.com
	Host mendi80
	IdentityFile C:/Users/Mendi/.ssh/mendi80
	# git clone git@mendi80:mendi80/simple-chat.git
	# git remote set-url origin git@mendi80:mendi80/simple-chat.git
	3) change name and email inside repo:
	git config -e



*html basic
	<!doctype html>
	<html lang="en">
	<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
	
	</style>
	<title>Mendi Website</title>
	</head>
		
	<body>
		
	<div id="div_main">
	</div>


	<script>
		elem_div=document.getElementById("div_main");
	</script>
	</body>
	</html>



# js performance
	var t0 = performance.now(), N=100;
	for (let i=0;i<N;i++) {}
	var t1 = performance.now();
	document.getElementById("myInfo").innerHTML = "runtime/N="+( (t1-t0)/N ).toFixed(3)+"ms";
	
** Context.php error
before: if (! class_exists($context))
add: $context= str_replace("Db100418","Db100300",$context); //mendi


x = await fetch('https://api.ipify.org').then(x=>x.text())


*sql create table

DROP TABLE IF EXISTS files;
DROP TABLE IF EXISTS forum_counters;
DROP TABLE IF EXISTS thread_counters;
DROP TABLE IF EXISTS users_counters;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS votes;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  user_id		INT UNSIGNED		NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nickname		VARCHAR(15) 		NOT NULL,
  created 		DATETIME 			NOT NULL,
  level			INT 				NOT NULL DEFAULT 99,
  manager_id	INT 				NOT NULL,
  secret		CHAR(60)	 		NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
-- INSERT INTO users (nickname,created,level,manager_id,secret) VALUES ("mendi80",NOW(),1,1,"$2y$10$84y5wBeQ4ZHhjvqtFRK.eubblRBroTT7E6VuUx4sbnR38jBOhdo0u");
-- UPDATE users SET secret = "$2y$10$84y5wBeQ4ZHhjvqtFRK.eubblRBroTT7E6VuUx4sbnR38jBOhdo0u" WHERE nickname = "mendi80"
-- handleUser.mad("*")   password_hash("8cf3b7df9ec6656fcd993959d6d61f64", PASSWORD_DEFAULT)
  
CREATE TABLE votes (
  vote_id		INT	UNSIGNED		NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id_src	INT	UNSIGNED		NOT NULL,
  user_id_tar	INT	UNSIGNED		NOT NULL,
  tmp			INT	UNSIGNED		NOT NULL,
  created 		DATETIME 			NOT NULL,
  score_type	TINYINT 			NOT NULL,
  score_value	TINYINT 			NOT NULL,
  quality_src	TINYINT 			NOT NULL,
  quality_tar	TINYINT 			NOT NULL,
  reason		VARCHAR(240)		NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE posts (
  post_id 		INT	UNSIGNED		NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ppost_id 		INT					NOT NULL CHECK (ppost_id >= 0),
  tmprpost_id	INT					NOT NULL CHECK (tmprpost_id >= 0),
  rpost_id 		INT					NOT NULL,
  user_id 		INT UNSIGNED		NOT NULL,
  nickname		VARCHAR(15) 		NOT NULL,
  created 		DATETIME 			NOT NULL,
  updated 		DATETIME 			NOT NULL,
  title 		VARCHAR(200)		NOT NULL,
  content		VARCHAR(2000)		NOT NULL,
  quality		INT					NOT NULL,
  blockedit		BIT					NOT NULL,
  blockreply	BIT					NOT NULL,
  CONSTRAINT 	FK_USERID_POSTS FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


 CREATE TABLE users_counters (
  user_id		INT	UNSIGNED		NOT NULL PRIMARY KEY,
  last_active	DATETIME			NOT NULL,
  n_threads		INT	UNSIGNED		NOT NULL,
  n_replies		INT UNSIGNED		NOT NULL,
  n_thrchars	INT UNSIGNED		NOT NULL,
  n_repchars	INT UNSIGNED		NOT NULL,
  CONSTRAINT	FK_USERID_USERCOUNTERS FOREIGN KEY (user_id) REFERENCES users(user_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
CREATE TABLE thread_counters (
  post_id 		INT	UNSIGNED		NOT NULL PRIMARY KEY,
  n_replies		INT	UNSIGNED		NOT NULL,
  n_edits		INT	UNSIGNED		NOT NULL,
  n_views 		INT	UNSIGNED		NOT NULL,
  updated		DATETIME			NOT NULL,
  CONSTRAINT	FK_POSTID_THREADS FOREIGN KEY (post_id) REFERENCES posts(post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- todo
CREATE TABLE forum_counters (
  forum_id 		INT	UNSIGNED		NOT NULL PRIMARY KEY AUTO_INCREMENT,
  n_connected	INT	UNSIGNED		NOT NULL,
  n_threads		INT	UNSIGNED		NOT NULL,
  n_replies		INT	UNSIGNED		NOT NULL,
  n_edits 		INT UNSIGNED		NOT NULL,
  n_views 		INT	UNSIGNED		NOT NULL,
  n_views 		INT	UNSIGNED		NOT NULL,
  updated 		DATETIME 			NOT NULL,
  created 		DATETIME 			NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO forum_counters (updated,created) VALUES (NOW(),NOW());


CREATE TABLE files (
  file_id 		INT	UNSIGNED	NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id		INT	UNSIGNED	NOT NULL,
  nickname		VARCHAR(15) 	NOT NULL,
  uploaded 		DATETIME 		NOT NULL,
  prefix		BIGINT			NOT NULL,
  extension		VARCHAR(7)		NOT NULL,
  basename		VARCHAR(63)		NOT NULL,
  CONSTRAINT 	FK_USERID_FILES FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TRIGGER IF EXISTS trig_afterinsert_users;
DROP TRIGGER IF EXISTS trig_beforeinsert_posts;
DROP TRIGGER IF EXISTS trig_afterinsert_posts;
DROP TRIGGER IF EXISTS trig_afterupdate_posts;

DELIMITER $$

CREATE TRIGGER trig_afterinsert_users AFTER INSERT ON users FOR EACH ROW 
	INSERT INTO users_counters (user_id, last_active) VALUES (NEW.user_id, NOW())$$

CREATE TRIGGER trig_beforeinsert_posts BEFORE INSERT ON posts FOR EACH ROW 
	SET NEW.rpost_id = IF(NEW.ppost_id=0,(SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA='outsiders' AND TABLE_NAME='posts'),NEW.tmprpost_id)$$

CREATE TRIGGER trig_afterinsert_posts AFTER INSERT ON posts FOR EACH ROW 
	IF (NEW.ppost_id=0) THEN 
		BEGIN
			INSERT INTO thread_counters (post_id, updated) VALUES (NEW.post_id, NOW());
			UPDATE forum_counters SET n_replies = n_replies + 1, updated = NOW() WHERE forum_id = 1;
			UPDATE users_counters SET n_threads=n_threads+1, n_thrchars=n_thrchars+LENGTH(NEW.content), last_active=NOW() WHERE user_id = NEW.user_id;
		END;
	ELSE
		BEGIN
			UPDATE thread_counters SET n_replies = n_replies + 1, updated = NOW() WHERE post_id = NEW.rpost_id;
			UPDATE users_counters SET n_replies=n_replies+1, n_repchars=n_repchars+LENGTH(NEW.content), last_active=NOW() WHERE user_id = NEW.user_id;
		END;
	END IF $$


CREATE TRIGGER trig_afterupdate_posts AFTER UPDATE ON posts FOR EACH ROW 
	BEGIN
		IF (NEW.ppost_id=0) THEN 
			UPDATE forum_counters SET n_edits = n_edits + 1, updated = NOW() WHERE forum_id = 1;
		END IF;
		UPDATE thread_counters SET n_edits = n_edits + 1, updated = NOW() WHERE post_id = NEW.rpost_id;
	END $$
    
DELIMITER ;