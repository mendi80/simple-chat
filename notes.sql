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
	
	<script>
	
	</script>
	</body>
</html>

*sql create table

DROP TABLE IF EXISTS threads;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  user_id		INT				NOT NULL PRIMARY KEY AUTO_INCREMENT,
  created 		DATETIME 		NOT NULL,
  nickname		VARCHAR(16) 	NOT NULL,
  secret		VARCHAR(16) 	NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE posts (
  post_id 		INT				NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ppost_id 		INT				NOT NULL CHECK (ppost_id >= 0),
  tmprpost_id	INT				NOT NULL CHECK (tmprpost_id >= 0),
  rpost_id 		INT				NOT NULL,
  user_id 		INT				NOT NULL,
  created 		DATETIME 		NOT NULL,
  updated 		DATETIME 		NOT NULL,
  nickname		VARCHAR(16) 	NOT NULL,
  secret		VARCHAR(16) 	NOT NULL,
  title 		VARCHAR(200)	NOT NULL,
  content		VARCHAR(2000)	NOT NULL,
  blockedit		INT				NOT NULL,
  blockreply	INT				NOT NULL,
  CONSTRAINT 	FK_USERID FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE threads (
  post_id 		INT				NOT NULL PRIMARY KEY,
  n_replies		INT				NOT NULL DEFAULT 0,
  n_edits 		INT				NOT NULL DEFAULT 0,
  updated 		DATETIME 		NOT NULL
-- here post_id could be zero.  CONSTRAINT 	FK_POSTID FOREIGN KEY (post_id) REFERENCES posts(post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- now add first root post 0
INSERT INTO threads (post_id, updated) VALUES (0, NOW());

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


