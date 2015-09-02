var http = require('http');
var app = require('http').createServer(handler)
var io = require('socket.io')(app);
var fs = require('fs');

var apiKey = 'shakkiratkonta_4700';



var Server = require('./server/Server')();

var server = new Server();
/*

server.addTournament({
  'key': 't12n',
  'starts_in': 10,
  'randomOrder': false,
  'starts_at': Date.now() + 15000,
  'collection': {
      'problems': [{
        'position': 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1',
        'solution': 'a2-a4'
        },
        {
        'position': 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq - 1 2',
        'solution': 'b7-b6'
        },
        {
        'position': 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq - 1 2',
        'solution': 'b7-b6'
        },
        {
        'position': 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq - 1 2',
        'solution': 'b7-b6'
        },        
      ],

  },

  'ratkaisuaika': 15,
  'pistelasku': true, 
});
*/

app.listen(8080);

function handler (req, res) {
  console.log("HTTP IN");
  fs.readFile(__dirname + '/index.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Error loading index.html');
    }

    res.writeHead(200);
    res.end(data);
  });
}

io.on('connection', function (socket) {
  console.log("CONNECTION IN")

  socket.on('join', function(data) {
    var user = server.addUser(data.username, socket);
    if (!user) {
      return socket.disconnect();
    }
    socket.user = user;
    user.handleParticipation(data);
  });

  socket.on('disconnect', function() {
    if (socket.user) {
      socket.user.die();
      socket.user = null;
    }
  });
});


var httpCallback = function(response) {
  var str = '';


  //another chunk of data has been recieved, so append it to `str`
  response.on('data', function (chunk) {
    str += chunk;
    console.log(chunk.toString());
  });

  //the whole response has been recieved, so we just print it out here
  response.on('end', function () {
    newTournamentSet(JSON.parse(str));
  });
}


function getLatestTournaments() {

  console.log("Making HTTP req for more tournaments");

  http.request({
    host: 'localhost',
    path: '/chessproblem/laravel/public/privateapi/startingtournaments/shakkiratkonta_4700'
  }, httpCallback).end();

  setTimeout(function() {
    getLatestTournaments();
  }, 30000);


}

function newTournamentSet(tournaments) {
  console.log("Tournaments received");
  console.log(tournaments);

  for (var i = tournaments.length - 1; i >= 0; i--) {
    var tournament = tournaments[i];
    server.addTournament(tournament);
  };
}

getLatestTournaments();



