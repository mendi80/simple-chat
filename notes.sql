
*ssl
apache/makecert.bat (add -node switch)
edit hhtp
chrome://flags/#allow-insecure-localhost
*todo

spammers
filtering framework/ voting system

limit image/video display size
change db names?
hide obfscure scramble js
dns website name

*security steps:
https://www.php.net/manual/en/security.hiding.php
setting expose_php=off in your php.ini
.htaccess in its files: php_flag engine off
add .php filter in .htaccess
disable browsing: httpd.conf
ddos: apache2.conf: RequestReadTimeout header=10-20,MinRate=500 body=20,MinRate=500
httpd.conf: disable mod_autoindex mod_dir cgi etc

*github clear history
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
	
	<div>
	</div>
	
	
	<script>
	
	</script>

	</body>
</html>

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
 
 
 



*old samples

create table tb_posts (
	idx int not null PRIMARY KEY AUTO_INCREMENT,
	date datetime not null,
	writer_name varchar(128) not null,
	subject varchar(128) not null,
	content varchar(1024) not null
);
create table author_names (
	idx int not null PRIMARY KEY AUTO_INCREMENT,
	first_name au not null
);
insert into livesearch (field) values (val1),(val2);


*insert rows to table
var row = table.insertRow(0);
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
cell1.innerHTML = val1;
cell2.innerHTML = val2;
cell3.innerHTML = val3;
row.innerHTML="<td>"+val1+"</td>"+"<td>"+val2+"</td>"+"<td>"+val3+"</td>";



*post
<iframe name="void" style="display:initial;"></iframe>
<form action="add_new_row.inc.php" method="POST" target="void">
	<input type="text" name="newrow" id="inputAddRow" placeholder="Add New Name...">
	<button type="submit" name="submit">Add</button>
</form>

* html,js button and create tb rows
<form>
	<input id="myinput">
	<button type="button" onclick="myOnButtonClick()">Submit</button>
	<p id="myinfo"> info </p>
	<table><tbody id="myTableBody"></tbody></table>
</form>
<script id="table-script">
	function addRowLocal(val1,val2,val3)
	{
		var t0 = performance.now();
		var newrow = "<tr><td>"+val1+"</td>"+"<td>"+val2+"</td>"+"<td>"+val3+"</td></tr>";
		var newrows="",i=0;
		for (i = 0; i < 1e2; i++) newrows+="<tr><td>"+i+"</td>"+"<td>"+val2+"</td>"+"<td>"+val3+"</td></tr>";
		var tb = document.getElementById("myTableBody");
		tb.innerHTML = newrows + tb.innerHTML;
		var t1 = performance.now();
		document.getElementById("myinfo").innerHTML = "runtime="+(t1-t0).toFixed(3)+"ms";
	}
</script>


# js performance
		var t0 = performance.now(), N=100;
		for (let i=0;i<N;i++) {}
		var t1 = performance.now();
		document.getElementById("myInfo").innerHTML = "runtime/N="+( (t1-t0)/N ).toFixed(3)+"ms";
		

# install xdebug
put phpinfo in : 
download dll to php/ext
in php.ini:
	[XDebug]
	xdebug.mode = debug
	xdebug.start_with_request = yes
	xdebug.client_port = 9000
	zend_extension = A:\Programs\xampp\php\ext\php_xdebug-3.0.3-8.0-vs16-x86_64.dll


<script id="script_cookies">
	var cookiesManager = new class{
		setCookie(cookiename,value,exp_days=365, fullpath=false) {
			document.cookie = `${cookiename}=${value}; expires=${(new Date(Date.now() + (exp_days*24*3600*1000))).toGMTString()}; ${fullpath?'path=/;':''}`;
		}	
		getCookie(cookiename){
			let ca = decodeURIComponent(document.cookie).split(';');
			for(let i = 0; i < ca.length; i++){
				let c = ca[i];
				while(c.charAt(0) == ' ') c = c.substring(1);
				if(c.startsWith(cookiename + "="))	return c.substring(cookiename.length + 1, c.length);
			}
			return "";
		}		
		getCookieOneLiner(cookiename){ //error if cookie not exists
			return decodeURIComponent(document.cookie).split(';').find(x => x.trim().startsWith(cookiename+'=')).split('=')[1];
		}
		deleteCookie(cookiename) {
			this.setCookie(cookiename,"empty",-3);
		}
	}
</script>



	(function () {
		if (typeof EventTarget !== "undefined") {
			let func = EventTarget.prototype.addEventListener;
			EventTarget.prototype.addEventListener = function (type, fn, capture) {
				this.func = func;
				if(typeof capture !== "boolean"){
					capture = capture || {};
					capture.passive = false;
				}
				this.func(type, fn, capture);
			};
		};
	}());
	
	

** Context.php error
before: if (! class_exists($context))
add: $context= str_replace("Db100418","Db100300",$context); //mendi


x = await fetch('https://api.ipify.org').then(x=>x.text())

