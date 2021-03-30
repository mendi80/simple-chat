*todo

filtering framework/ voting system


limit image/video display size
hide php htaccess
change db names?
hide obfscure scramble js
dns website name


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
DROP TABLE IF EXISTS threads;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS votes;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  user_id		INT UNSIGNED		NOT NULL PRIMARY KEY AUTO_INCREMENT,
  created 		DATETIME 			NOT NULL,
  quality0		TINYINT UNSIGNED	NOT NULL,
  quality1		TINYINT UNSIGNED	NOT NULL,
  quality2		TINYINT UNSIGNED	NOT NULL,
  quality3		TINYINT UNSIGNED	NOT NULL,
  okwrite		BIT					NOT NULL,
  okreply		BIT					NOT NULL,
  okreply2		BIT					NOT NULL,
  okreply3		BIT					NOT NULL,
  okreply4		BIT					NOT NULL,
  okreply5		BIT					NOT NULL,
  okreply6		BIT					NOT NULL,
  okreply7		BIT					NOT NULL,
  nickname		VARCHAR(15) 		NOT NULL,
  secret		VARCHAR(63) 		NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
 CREATE TABLE users_counters (
  user_id		INT	UNSIGNED		NOT NULL PRIMARY KEY,
  nposts		INT	UNSIGNED		NOT NULL,
  nreplies		INT UNSIGNED		NOT NULL,
  ncharacters	INT UNSIGNED		NOT NULL,
  nreads		INT UNSIGNED		NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
 CREATE TABLE users_actions (
  user_id		INT	UNSIGNED		NOT NULL PRIMARY KEY,
  action_id		INT	UNSIGNED		NOT NULL,
  target_id		INT UNSIGNED		NOT NULL,
  action_value	INT UNSIGNED		NOT NULL,
  created 		DATETIME 			NOT NULL,
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE votes (
  vote_id		INT	UNSIGNED		NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id_src	INT	UNSIGNED		NOT NULL,
  user_id_tar	INT	UNSIGNED		NOT NULL,
  user_id_tar	INT	UNSIGNED		NOT NULL,
  created 		DATETIME 			NOT NULL,
  score_type	TINYINT 			NOT NULL,
  score_value	TINYINT 			NOT NULL,
  quality_src	TINYINT UNSIGNED	NOT NULL,
  quality_tar	TINYINT UNSIGNED	NOT NULL,
  reason		VARCHAR(240)		NOT NULL,
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE posts (
  post_id 		INT	UNSIGNED		NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ppost_id 		INT					NOT NULL CHECK (ppost_id >= 0),
  tmprpost_id	INT					NOT NULL CHECK (tmprpost_id >= 0),
  rpost_id 		INT					NOT NULL,
  user_id 		INT UNSIGNED		NOT NULL,
  created 		DATETIME 			NOT NULL,
  updated 		DATETIME 			NOT NULL,
  nickname		VARCHAR(15) 		NOT NULL,
  secret		VARCHAR(15) 		NOT NULL,
  title 		VARCHAR(200)		NOT NULL,
  content		VARCHAR(2000)		NOT NULL,
  quality		INT					NOT NULL,
  blockedit		BIT					NOT NULL,
  blockreply	BIT					NOT NULL,
  CONSTRAINT 	FK_USERID_POSTS FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE threads (
  post_id 		INT	UNSIGNED		NOT NULL PRIMARY KEY,
  n_replies		INT					NOT NULL DEFAULT 0,
  n_edits 		INT					NOT NULL DEFAULT 0,
  updated 		DATETIME 			NOT NULL
-- here post_id could be zero.  CONSTRAINT 	FK_POSTID FOREIGN KEY (post_id) REFERENCES posts(post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- now add first root post 0
INSERT INTO threads (post_id, updated) VALUES (0, NOW());


CREATE TABLE files (
  file_id 		INT	UNSIGNED	NOT NULL AUTO_INCREMENT PRIMARY KEY,
  uploaded 		DATETIME 		NOT NULL,
  user_id		INT	UNSIGNED	NOT NULL,
  nickname		VARCHAR(15) 	NOT NULL,
  prefix		BIGINT			NOT NULL,
  extension		VARCHAR(7)		NOT NULL,
  basename		VARCHAR(63)		NOT NULL,
  CONSTRAINT 	FK_USERID_FILES FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





DROP TRIGGER IF EXISTS trig_beforeinsert_posts;
DROP TRIGGER IF EXISTS trig_afterinsert_posts;
DROP TRIGGER IF EXISTS trig_afterupdate_posts;

DELIMITER $$
CREATE TRIGGER trig_beforeinsert_posts BEFORE INSERT ON posts FOR EACH ROW 
	SET NEW.rpost_id = IF(NEW.ppost_id=0,(SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA='mendi_forum_01' AND TABLE_NAME='posts'),NEW.tmprpost_id) $$

CREATE TRIGGER trig_afterinsert_posts AFTER INSERT ON posts FOR EACH ROW 
	IF (NEW.ppost_id=0) THEN 
		BEGIN
			UPDATE threads SET n_replies = n_replies + 1, updated = NOW() WHERE post_id = 0;
			INSERT INTO threads (post_id, updated) VALUES (NEW.post_id, NOW());
		END;
	ELSE 
		UPDATE threads SET n_replies = n_replies + 1, updated = NOW() WHERE post_id = NEW.rpost_id;
	END IF $$


CREATE TRIGGER trig_afterupdate_posts AFTER UPDATE ON posts FOR EACH ROW 
	BEGIN
		IF (NEW.ppost_id=0) THEN 
			UPDATE threads SET n_edits = n_edits + 1, updated = NOW() WHERE post_id = 0;
		END IF;
		UPDATE threads SET n_edits = n_edits + 1, updated = NOW() WHERE post_id = NEW.rpost_id;
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





