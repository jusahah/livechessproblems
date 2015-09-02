var jQuery = require('jquery');
//var io = require('socket.io');

var Socket = require('./client/ClientSocket')();

//var Server = require('./server/Server')();

var SingleClient = require('./client/SingleClient')();

var c1 = new SingleClient(jQuery('#client1Window'));

var username = jQuery('#tournamentWindow').attr('data-username');
var tournamentKey = jQuery('#tournamentWindow').attr('data-tournamentkey');

//alert(tournamentKey);

c1.username = username;

// Create server for testing


// Setup test clients as test tournament's users...
// In production this is done by template engine
c1.tournamentKey = tournamentKey;



/* Socket setup starts */
var realSocket = io('http://localhost:8080');


var s1 = new Socket(c1, 'client', realSocket);

c1.socket = s1;

c1.init();

//c1.socket.setEndpoint(server.socketFor('p1'));


/* Socket setup ready */

/* Init users */


/*
// Prepare test clients
var c1 = new SingleClient(jQuery('#client1Window'));
var c2 = new SingleClient(jQuery('#client2Window'));
var c3 = new SingleClient(jQuery('#client3Window'));
var c4 = new SingleClient(jQuery('#client4Window'));

var username = $('#tournamentWindow').attr('data-username');
var tournamentKey = $('#tournamentWindow').attr('data-tournamentkey');

c1.username = username;
c2.username = username;
c3.username = username;
c4.username = username;

// Create server for testing
var server = new Server();
server.addTournament({
	'key': 'xxx1',
	'starts_in': 10,
	'randomOrder': false,
	'starts_at': Date.now() + 10000,
	'problems': [{
		'fen': 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1',
		'solution': 'a2-a4'
		},
		{
		'fen': 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq - 1 2',
		'solution': 'b7-b6'
		},
		{
		'fen': 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq - 1 2',
		'solution': 'b7-b6'
		},
		{
		'fen': 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq - 1 2',
		'solution': 'b7-b6'
		},				
	],
	'solvetime': 15,
	'timegrading': true, 
});

// Setup test clients as test tournament's users...
// In production this is done by template engine
c1.tournamentKey = tournamentKey;
c2.tournamentKey = tournamentKey;
c3.tournamentKey = tournamentKey;
c4.tournamentKey = tournamentKey;




var s1 = new Socket(c1, 'client');
var s2 = new Socket(c2, 'client');
var s3 = new Socket(c3, 'client');
var s4 = new Socket(c4, 'client');

c1.socket = s1;
c2.socket = s2;
c3.socket = s3;
c4.socket = s4;

server.addUser('p1');
server.addUser('p2');
server.addUser('p3');
server.addUser('p4');

server.socketFor('p1').setEndpoint(s1);
server.socketFor('p2').setEndpoint(s2);
server.socketFor('p3').setEndpoint(s3);
server.socketFor('p4').setEndpoint(s4);

c1.socket.setEndpoint(server.socketFor('p1'));
c2.socket.setEndpoint(server.socketFor('p2'));
c3.socket.setEndpoint(server.socketFor('p3'));
c4.socket.setEndpoint(server.socketFor('p4'));





setTimeout(function() {
	c3.init();
	c4.init();	
}, 1000);

setTimeout(function() {
	c1.init();	
}, 3450);
setTimeout(function() {
	c2.init();	
}, 5450);

*/





