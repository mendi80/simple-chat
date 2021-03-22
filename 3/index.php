
<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--link rel="stylesheet" href="style.css"-->
	<style> 
		// {  width:80vw; padding:0; margin:0 auto;  box-sizing: border-box; }
		body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
		body { background: linear-gradient( 45deg, #1e7752, #e73c7e, #23a6d5, #23d5ab); background-attachment: fixed; background-size: 400% 400%; animation: AnimationName 10s ease infinite;}
		@keyframes AnimationName { 0%{background-position:0% 50%}  50%{background-position:100% 50%}  100%{background-position:0% 50%} }

		h1 { margin-top:2vh; padding:0; text-align:center; text-transform: uppercase;  font-weight: 300; font-size:4vw; letter-spacing: 0.2vw; color:#a1215170; }
		h1::after { content:""; display:flex; border-top: 2px solid #585858; margin-top: .2em;}
		#user_namepwd {width: 100px; align:"left";}
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
		tbody.forumTitles tr:hover {background-color: #fbc93d; cursor:default;}


		article {
				white-space: pre-wrap;
			}
	</style>
	<title>Live</title>
</head>

<body>
<!-- *************************************************************BODY****************************************************************** -->
<h1 id='datetime' />
<h3 id="myInfo" style="display:block">-</h3>
<h3 id="myInfo2" style="display:block">-</h3>

<div id="user_namepwd">
	<input type="text" id="inputUserNickname" placeholder="כינוי">
	<input type="password" id="inputUserPassword" placeholder="ססמה">
</div>
<div id="div_fetchtitles" dir="rtl" style="display:none">
	<input type="checkbox" id="chkFetchTitles" name="FetchTitles" checked="true">	<!--label for="FetchTitles"> AutoUpdate</label--><br>
	<button type="button" id="btnCreatePost" onclick="btnCreatePost()">כתוב הודעה חדשה</button>
	<table>
		<thead><th>כותרת</th><th>שם הכותב</th><th>תאריך כתיבה</th><th>צפיות</th></thead>
		<tbody class="forumTitles" id="forumtitles"></tbody>
	</table>
</div>

<div id="div_readingpost" style="display:none">
	<button type="button" id="btnCancelPost" onclick="btnCancelPost()">בטל וחזור</button>
	<button type="button" id="btnEditPostFromReading" onclick="btnEditPostFromReading()">ערוך הודעה</button>
	<p id="readingPostID" class="PostID"></p>
	<h2 id="readingPostTitle" dir="rtl" class="PostTitle"></h1>
	<article id="readingPostContent" dir="rtl" class="PostContent"></article>
</div>

<div id="div_writingpost" style="display:none">
	<button type="button" id="btnCancelPost" onclick="btnCancelPost()">בטל</button>
	<p id="writingPostID" class="PostID"></p>
	<input type="text" id="writingPostTitle" placeholder="Title" oninput="oninputTitle()">
	<textarea id="writingPostContent" name="postcontent" dirname="postcontent.dir" placeholder="תוכן" oninput="oninputContent()" rows="20" cols="50" maxlength="1000" > </textarea>
	<h2 id="previewPostTitle" dir="rtl" class="PostTitle"></h1>
	<article id="previewPostContent" dir="rtl" class="PostContent"></article>
	<button type="button" id="buttonAddRow" onclick="btnSendPost()">Add</button>
</div>


<!-- ************************************************************SCRIPTS******************************************************************* -->

<script id="script_state">
	myInfo=document.getElementById("myInfo");
	myInfo2=document.getElementById("myInfo2");
	function setInfo(x) {myInfo.innerHTML=x;}
	function setInfo2(x) {myInfo2.innerHTML=x;}

	var isMobile = 'ontouchstart' in window;
	const pagestates = {INIT:"Init",FETCHTITLES:"FectTitles", READINGPOST:"ReadingPost", WRITINGPOST:"WritingPost", EDITINGPOST:"EditingPost"}
	const forum_div_ids = new Array( "div_fetchtitles", "div_writingpost", "div_readingpost");
	var pagestate = pagestates.INIT;
	
	function setPageState(newstate)
	{
		if (Object.values(pagestates).indexOf(newstate) <= 0) // init also illegael, otherwise <= -1 will include init
   			console.error(`error in setPageState. newstate=${newstate} not exist`);
		let prev_pagestate = pagestate;
		pagestate = newstate;

		for (let i = 0, len = forum_div_ids.length; i < len; i++) document.getElementById(forum_div_ids[i]).style.display = "none";

		switch(pagestate)
		{
			case pagestate.INIT: 
				console.error('setPageState init error');
				break;
			case pagestates.FETCHTITLES:			
				document.getElementById("div_fetchtitles").style.display = "block";
				break;
			case pagestates.READINGPOST:	
				document.getElementById("div_readingpost").style.display = "block";
				break;
			case pagestates.WRITINGPOST:
				document.getElementById("div_writingpost").style.display = "block";
				break;
			case pagestates.EDITINGPOST:
				document.getElementById("div_writingpost").style.display = "block";
				break;
		}
	}

	function btnCreatePost(){
		document.getElementById("readingPostID").innerHTML="";
		setPageState(pagestates.WRITINGPOST) 
	}

	function btnCancelPost(){
		setPageState(pagestates.FETCHTITLES);
	}

	function btnEditPostFromReading()
	{
		document.getElementById("writingPostID").innerHTML = document.getElementById("readingPostID").innerHTML;
		document.getElementById("writingPostTitle").value = document.getElementById("readingPostTitle").innerHTML;
		document.getElementById("writingPostContent").value = document.getElementById("readingPostContent").innerHTML;
		oninputTitle(); //prepare preview
		oninputContent(); //prepare preview
		setPageState(pagestates.EDITINGPOST);
	}

	function btnSendPost()
	{
		sendPost();
	}

</script>

<script id="script_clearcontent"> 
	function clearPostContent()
	{
		document.getElementById("writingPostTitle").value="";
		document.getElementById("writingPostContent").value="";
	}
</script>

<script id="script_readingpost">

	function displayPost(data)
	{
		//post_id, created_datetime, nickname, title, content
		document.getElementById("readingPostID").innerHTML=data["post_id"];
		document.getElementById("readingPostTitle").innerHTML=data["title"];
		document.getElementById("readingPostContent").innerHTML=data["content"];
		setPageState(pagestates.READINGPOST);
		let tPostRequestEnd = performance.now();
		setInfo( (tPostRequestEnd-tPostRequestStart).toFixed(1)+"ms");
		setInfo2(data["runtime"]);
	}

	var tTitleTouchStarted = performance.now();
	var tTitleTouchEnded = performance.now();
	var tScrolled = performance.now();
	var tPostRequestStart = performance.now();
	const reqPostContent = new FormData();
	reqPostContent.set('op', "getpost");
	reqPostContent.set('post_id', "");
	function titleRowClicked(post_id)
	{
		tTitleTouchEnded = performance.now();
		if(!isMobile || tTitleTouchEnded-tTitleTouchStarted<300 && tTitleTouchEnded-tScrolled>200)
		{
			tPostRequestStart = performance.now();
			reqPostContent.set('post_id', post_id);
			fetch('./op.php', {method: 'POST', body: reqPostContent})
			.then(res => res.json())
			.then(res => displayPost(res))
			.catch(e => console.error('Error, titleRowClicked(), ' + e))
		}
	}


</script>


<script id="script_writingpost">

	elem_writingPostTitle=document.getElementById("writingPostTitle");
	elem_writingPostContent=document.getElementById("writingPostContent");
	elem_previewPostTitle=document.getElementById("previewPostTitle");
	elem_previewPostContent=document.getElementById("previewPostContent");
	//console.time();for (i=0;i<1000;i++) elem_previewPostContent.dir=elem_writingPostContent.dir;console.timeEnd();

	function oninputTitle()
	{
		elem_previewPostTitle.innerHTML = elem_writingPostTitle.value;
		elem_previewPostTitle.dir = elem_writingPostTitle.dir;
	}
	function oninputContent()
	{
		elem_previewPostContent.innerHTML = elem_writingPostContent.value;
		elem_previewPostContent.dir = elem_writingPostContent.dir;
	}

	const newPost = new FormData();
	newPost.set('op', "setpost");
	newPost.set('post_id', "");
	newPost.set('nickname', "");
	newPost.set('pwd', "");
	newPost.set('title', "");
	newPost.set('content', "");

	function sendPost() {
		newPost.set('post_id', document.getElementById("writingPostID").innerHTML);
		newPost.set('nickname',document.getElementById("inputUserNickname").value);
		newPost.set('pwd',document.getElementById("inputUserPassword").value);
		newPost.set('title',document.getElementById("writingPostTitle").value);
		newPost.set('content',document.getElementById("writingPostContent").value);

		fetch('./op.php', {method: 'POST', body: newPost})
		.then(res => res.text())
		.then(res => {console.log(res);if (res=="1") {clearPostContent(); setPageState(pagestates.FETCHTITLES); }})
		.catch(e => console.error('Error, sendPost(), ' + e))
	}
</script>


<script id="script_timer_fetchtitles">
	chkFetchTitles = document.getElementById("chkFetchTitles");
	forumtitles = document.getElementById("forumtitles");
	const pingFormData = new FormData(); 
	pingFormData.set('op', "gettitles");
	pingFormData.set('clientUpdatedTime', "hmm");
	let touchEvent = isMobile ? 'ontouchstart=touchTitleStarted() ontouchend' : 'onclick';

	function updateForumTitles(data) {
		if (!Array.isArray(data)) return;
		let new_rows=""; 
		const LAST_ELEMENT=data.length-1;
		for (let i=LAST_ELEMENT-1; i>=0; i--) 
		{
			d=data[i];
			new_rows += `\t<tr ${touchEvent}=titleRowClicked(${d["post_id"]})><td>${d["title"]}</td><td>${d["nickname"]}</td><td>${d["created_datetime"]}</td><td>${5349}</td>\n`;
		}
		pingFormData.set('clientUpdatedTime', data[LAST_ELEMENT]['serverUpdatedTime']);
		forumtitles.innerHTML = new_rows;
	}

	function pingAndFetch() {
		var t0 = performance.now();
		fetch('./op.php', {method: 'POST', body: pingFormData })
		.then(res => {console.log(res); return res;})
		.then(res => res.json())
		.then(res => updateForumTitles(res))
		.catch(e => console.error('Error, pingAndFetch(), ' + e));
		var t1 = performance.now();
		console.log(`pingAndFetch took ${(t1-t0).toFixed(1)} ms`);
		
	}

	var titles_datetime = new Date();
	function forumTimer()
	{
		setTimeout(forumTimer, 2*1000);
		if (chkFetchTitles.checked)	
		{
			pingAndFetch();
			titles_datetime.setTime(Date.now());
			document.getElementById('datetime').innerHTML = titles_datetime.toDateString() + ' ' + titles_datetime.toLocaleTimeString();
			chkFetchTitles.checked = false;
		}
	}
   
	
	function touchTitleStarted() { tTitleTouchStarted = performance.now();}
	window.onscroll = function() { tScrolled = performance.now(); }; //var y = window.scrollY
</script>

<script id="script_init">
{
	setPageState(pagestates.FETCHTITLES);
	forumTimer();
}
</script>

<!--?=PHP_EOL;?-->
</body>
</html>
 