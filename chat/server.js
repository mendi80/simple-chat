//added date

const express = require('express');
const http = require('http');
const WebSocket = require('ws');
const fs = require('fs');

const port = 8088;
const chatfilepath = 'chat.txt';

var chatmem = fs.existsSync(chatfilepath) ? fs.readFileSync(chatfilepath).toString().replace(/\r\n/g,'\n').split('\n') : new Array();
if (chatmem.length>0 && chatmem[chatmem.length-1].length==0) chatmem.pop();
var chatfile = fs.createWriteStream(chatfilepath, {flags: 'a'});


const app = express();
const server = http.createServer(app);
const wss = new WebSocket.Server({server});

var count_users = 0;
wss.on('connection', function connection(ws){
	const useridx = ++count_users;
	console.log(`new connection, user=${useridx}, ${ws._socket.remoteAddress}:${ws._socket.remotePort}`);
	chatmem.forEach(d => ws.send(d));
	ws.on('message', function incoming(msgbuf){
		let msgstr=msgbuf.toString();
		try { msgjson = JSON.parse(msgstr);} catch (e) {return console.error(e); }
		msgjson.date = Date.now();
		data=JSON.stringify(msgjson);
		chatmem.push(data);
		chatfile.write(data+'\n');
		//console.log(`new msg (type=${typeof(data)}): "${data}" sent from ${useridx}, ${ws._socket.remoteAddress}:${ws._socket.remotePort}`);
		wss.clients.forEach(function each(client){
			(client !== ws && client.readyState === WebSocket.OPEN)? client.send(data): 0;
		})
	})
})


server.listen(port, 'localhost', function(err){
	console.log(err ? err: `Server listening on port ${server.address().address}:${server.address().port} , mode=${app.settings.env}`);
})