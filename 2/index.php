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
		td, th {border: 1px solid #dddddd; text-align: left; padding: 8px;}
		tr:nth-child(even) {background-color: #dddddd;}
		*/
	</style>
	<title>Live</title>
</head>

<body>

<h1 id='time'></h1>
<h3 id="myInfo">-</h3>

<form id="form_addrowtodb">
	<input type="text" id="inputAddRow" placeholder="Add New Name...">
	<br/>
	<button type="button" id="buttonAddRow" onclick="addRowTodatabase()">Add</button>
</form>

<form id="form_quicksearch">
	<input type="text" id="searchBox" oninput=fetchQuickSearch(this.value) placeholder="Search here...">
	<ul id="searchResult"> </ul>
</form>


<script id="script_addrowtodb">
	const rowtoaddFormData = new FormData(); rowtoaddFormData.set('newrow', "");
	const row_to_add = document.getElementById("inputAddRow");
	function addRowTodatabase() {
		rowtoaddFormData.set('newrow',row_to_add.value);
		fetch('./action.add_new_row.inc.php', {method: 'POST', body: rowtoaddFormData})
		.then(res => res.text())
		.then(res => {console.log(res);if (res) row_to_add.value="";})
		.catch(e => console.error('Error, fetchQuickSearch(), ' + e))
		row_to_add.focus();
	}
</script>

<script id="script_livesearch">
	const searchFormData = new FormData(); searchFormData.set('searchname', "");
	const searchResult = document.getElementById("searchResult");
	function fetchQuickSearch(name) {
		searchFormData.set('searchname',name);
		fetch('./action.search.inc.php', {method: 'POST', body: searchFormData })
		.then(res => res.json())
		.then(res => displaySearchResult(res))
		.catch(e => console.error('Error, fetchQuickSearch(), ' + e))
	}

	function displaySearchResult(data) {
		let new_rows=""; for (let i=0; i<data.length; i++) new_rows += "\t<li>"+data[i]["first_name"]+"</li>\n";
		searchResult.innerHTML = new_rows;
	}
</script>

<script id="script_init">
{
	let date = new Date();
	let n = date.toDateString();
	let time = date.toLocaleTimeString();
	document.getElementById('time').innerHTML = n + ' ' + time;
	fetchQuickSearch("");
}
</script>

<?=PHP_EOL;?>
</body>
</html>
 