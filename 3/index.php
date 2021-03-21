<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--link rel="stylesheet" href="style.css"-->
	<style> 
		* {  width:80vw; padding:0; margin:0 auto;  box-sizing: border-box; }
		body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
		body { background: linear-gradient( 45deg, #1e7752, #e73c7e, #23a6d5, #23d5ab); background-attachment: fixed; background-size: 400% 400%; animation: AnimationName 10s ease infinite;}
		@keyframes AnimationName { 0%{background-position:0% 50%}  50%{background-position:100% 50%}  100%{background-position:0% 50%} }

		h1 { margin-top:2vh; padding:0; text-align:center; text-transform: uppercase;  font-weight: 300; font-size:4vw; letter-spacing: 0.2vw; color:#a1215170; }
		h1::after { content:""; display:flex; border-top: 2px solid #585858; margin-top: .2em;}
		#inputAddRow { height:48px; background:#bfb5; border:none; padding: 8px 18px; font-family:"Raleway", sans-serif; font-size:32px; }
		#inputAddRow:focus {outline:none;}
		#buttonAddRow {background-color:#aaaaaaaa; height:64px; font-size:32px; display:inline-block; border:0.1em solid #FFFFFF; margin:2px auto 10px auto;
							border-radius:5px;  font-family:'Roboto',sans-serif; font-weight:300; color:#000; text-align:center; transition: all 0.5s;}		
		#buttonAddRow:hover {color:#f00;background-color:#527;}
		#searchBox { height:48px; background:#fff; border:none; padding: 8px 18px; font-family:"Raleway", sans-serif; font-size:32px; }
		#searchBox:focus {outline:none;}
		#searchResult {padding:32px; list-style:none; background:#555; color:#fff; height:400px; overflow-y:scroll;}
		#searchResult li {padding:8px 0px; border-left: 1px solid #645f5f; padding-left: 24px;}
		#searchResult::-webkit-scrollbar { width:8px; background:#111;}
		#searchResult::-webkit-scrollbar-thumb { width:8px; background:#880;}
		table {font-family: arial, sans-serif; border-collapse: collapse; width: 100%;}
		th {border: 1px solid #dddddd; text-align: center; padding: 8px;}
		td {border: 1px solid #dddddd; text-align: right; padding: 8px;}
		tr:nth-child(even) {background-color: #dddddd;}
		*/
	</style>
	<title>Live</title>
</head>

<body>

<h1 id='time'></h1>
<h3 id="myInfo">-</h3>

<div id="user_namepwd">
	<input type="text" id="inputUserNickname" placeholder="כינוי">
	<input type="password" id="inputUserPassword" placeholder="ססמה">
</div>
<div id="normal_state" dir="rtl">
	<input type="checkbox" id="chkFetchTitles" name="FetchTitles" checked="true">
	<!--label for="FetchTitles"> AutoUpdate</label--><br>
	<button type="button" id="btnCreatePost" onclick="btnCreatePost()">כתוב הודעה חדשה</button>
	<table>
		<thead><th>כותרת</th><th>שם הכותב</th><th>תאריך כתיבה</th><th>צפיות</th></thead>
		<tbody id="forumtitles"></tbody>
	</table>

</div>
<div id="writingpost_state">
	<button type="button" id="btnCancelPost" onclick="btnCancelPost()">בטל</button>
	<input type="text" id="inputPostTitle" placeholder="Title">
	<textarea id="inputPostContent" name="postcontent" dirname="postcontent.dir" placeholder="תוכן" rows="20" cols="50" maxlength="1000" > </textarea>
	<button type="button" id="buttonAddRow" onclick="sendPost()">Add</button>
</div>

<script id="script_pagestates">
	const pagestates={INIT:"Init",NORMAL:"Normal",WRITINGPOST:"WritingPost"}
	var pagestate = pagestates.INIT;

	function setPageState(newstate)
	{
		if (Object.values(pagestates).indexOf(newstate) <= -1) 
   			console.error('error in setPageState. newstate not exist');
		else
			pagestate=newstate;
			
		switch(pagestate)
		{
			case pagestate.INIT:
			case pagestates.NORMAL:
				document.getElementById("normal_state").style.display = "block";
				document.getElementById("writingpost_state").style.display = "none";
				break;

			case pagestates.WRITINGPOST:
				document.getElementById("normal_state").style.display = "none";
				document.getElementById("writingpost_state").style.display = "block";
				break;

		}
	}

	function btnCreatePost(){	setPageState(pagestates.WRITINGPOST) }
	function btnCancelPost(){	setPageState(pagestates.NORMAL)	}

</script>
<script id="script_clearcontent"> inputPostTitle
	function clearPostContent()
	{
		document.getElementById("inputPostTitle").value="";
		document.getElementById("inputPostContent").value="";
	}
</script>
<script id="script_addnewposttodb">
	const newPost = new FormData();
	newPost.set('nickname', "");
	newPost.set('pwd', "");
	newPost.set('title', "");
	newPost.set('content', "");

	function sendPost() {
		newPost.set('nickname',document.getElementById("inputUserNickname").value);
		newPost.set('pwd',document.getElementById("inputUserPassword").value);
		newPost.set('title',document.getElementById("inputPostTitle").value);
		newPost.set('content',document.getElementById("inputPostContent").value);

		fetch('./action.add_new_post.inc.php', {method: 'POST', body: newPost})
		.then(res => res.text())
		.then(res => {console.log(res);if (res) clearPostContent();})
		.catch(e => console.error('Error, sendPost(), ' + e))
	}
</script>


<script id="script_forumtimer">
	chkFetchTitles = document.getElementById("chkFetchTitles");
	forumtitles = document.getElementById("forumtitles");
	const pingFormData = new FormData(); pingFormData.set('clientUpdatedTime', "hmm");

	function updateForumTitles(data){
		if (!Array.isArray(data)) return;
		let new_rows=""; 
		const LAST_ELEMENT=data.length-1;
		for (let i=0; i<LAST_ELEMENT; i++) 
		{
			d=data[i];
			new_rows += `\t<tr><td>${d["title"]}</td><td>${d["nickname"]}</td><td>${d["created_datetime"]}</td><td>${5349}</td>\n`;
		}
		pingFormData.set('clientUpdatedTime', data[LAST_ELEMENT]['serverUpdatedTime']);
		forumtitles.innerHTML = new_rows;
	}

	function pingAndFetch() {
		fetch('./action.pingfetch.inc.php', {method: 'POST', body: pingFormData })
		.then(res => res.json())
		.then(res => updateForumTitles(res))
		.catch(e => console.error('Error, pingAndFetch(), ' + e))
	}
	function forumTimer()
	{
		setTimeout(forumTimer, 10*1000);
		if (chkFetchTitles.checked)	pingAndFetch();
	}

</script>

<script id="script_init">
{
	let date = new Date();
	let n = date.toDateString();
	let time = date.toLocaleTimeString();
	document.getElementById('time').innerHTML = n + ' ' + time;
	setPageState(pagestates.NORMAL);
	forumTimer();
}
</script>

<?=PHP_EOL;?>
</body>
</html>
 