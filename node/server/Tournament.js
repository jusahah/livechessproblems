var ProblemSet = require('./ProblemSet')();
var Standings = require('./Standings')();
var Round = require('./Round')();
var NormalGrader = require('./NormalGrader')();
var TimeGrader = require('./TimeGrader')();

// Magic numbers
var WAITING_TIME_BETWEEN_ROUNDS = 10000;

function Tournament(server, data) {

	this.mediator = server;

	this.key = data.key;

	this.starts_at = isNaN(data.starts_at) ? Date.parse(data.starts_at) : data.starts_at;

	this.players = [];
	this.kibitzers = [];

	this.problemSet = new ProblemSet(data.collection.problems, data.randomOrder);
	this.solveTime = data.ratkaisuaika;
	this.timeGrading = data.pistelasku == '1';

	this.mode;

	this.currentRound;
	this.roundCounter = 1;

	this.timeTillStartInterval = null;

	this.broadcastPlayers = function(msg) {

		for (var i = this.players.length - 1; i >= 0; i--) {
			this.players[i].msgToSocket(msg);
		};

	}

	this.ensurePlayersRemoveTournamentLink = function() {
		for (var i = this.players.length - 1; i >= 0; i--) {
			this.players[i].removeTournamentLink(this);
		};
	}

	this.finish = function() {
		console.log('TOURNAMENT: Finishing up...');
		this.mode = 'finished';
		this.broadcastPlayers({tag: 'standings', key: this.key, state: this.mode, standings: this.standings.finalStandings(this.players.slice())});
		this.broadcastPlayers({tag: 'finished', data: {key: this.key}});
		// Notify server that we are so done here.
		this.ensurePlayersRemoveTournamentLink();
		this.mediator.tournamentDone(this);
	}

	this.roundLoop = function() {
		if (!this.problemSet.hasProblemsLeft()) {
			return setTimeout(function() {
				this.finish();
			}.bind(this), 0);
		}

		// We create here Round object. It notifies us back when round is over, and then
		// we loop back here again if there are still problems left.

		setTimeout(function() {
			var problem = this.problemSet.getNextProblem();
			this.currentRound = new Round(this, problem, this.solveTime, this.roundCounter++, this.players.slice());
			this.mode = "waitingForAnswers";
			var problemData = this.currentRound.getProblemDataForClients();
			// Decorating outgoing problem data with tournament key and state
			problemData.key = this.key;
			problemData.state = this.mode;
			problemData.tag = 'newProblem';
			this.broadcastPlayers(problemData);
		}.bind(this), WAITING_TIME_BETWEEN_ROUNDS);
	}


	this.roundWantsToDie = function(round) {

		if (round !== this.currentRound) {
			console.log("FATAL ERROR: Wrong round object send die msg to tournament object!");
			return false;
		}

		console.log("ROUND DYING RECEIVED IN TOURNAMENT");

		var results = this.currentRound.getResults();
		this.standings.addNewResults(results);
		this.mode = 'waitingForNextRound';
		this.broadcastPlayers({tag: 'standings', key: this.key, state: this.mode, standings: this.standings.overView(this.players.slice())});
		this.roundLoop();

	}

	this.incomingAnswer = function(answer) {
		console.log("-----------------------------")
		console.log("-----------------------------")
		console.log("ANSWER GOT INTO TOURNAMENT");
		console.log(answer);
		console.log("MODE: " + this.mode)
		console.log("KEY: " + this.key)

		if (this.mode !== 'waitingForAnswers') return false;
		if (answer.key !== this.key) return false;

		var result = this.currentRound.answerIn(answer);
				console.log("-----------------------------")
		console.log("-----------------------------")

		// if result = 1 -> right answer, result = 0 -> wrong, result = false -> invalid
		// Perhaps broadcast all players the result of user's answer

		this.broadcastPlayers(this.currentRound.answersGivenSoFar());

		return result;


	} 

	this.userLeft = function(user) {

		console.log("------------Removing left user from tournament---------");

		var i = this.players.indexOf(user);

		if (i !== -1) {
			this.players.splice(i, 1);
		}
	}

	this.start = function() {

		if (this.timeTillStartInterval) {
			clearInterval(this.timeTillStartInterval);
			this.timeTillStartInterval = null;
		}
		var newGrader;
		if (this.timeGrading) {
			newGrader = new TimeGrader();
		} else {
			newGrader = new NormalGrader();
		}

		this.standings = new Standings(this, this.players, newGrader);

		if (this.players.length === 0) {
			console.log("Tournament starting aborted - no players participating");
			setTimeout(function() {
				this.finish();
			}.bind(this), 0)
			return false;
		}

		this.mode = 'waitingForNextRound';
		this.broadcastPlayers({tag: 'standings', key: this.key, state: this.mode, standings: this.standings.overView(this.players.slice())});
		this.roundLoop();
	}

	this.register = function(user) {

		// If state is "waitingforRegistrationToStart", we could return info msg ("Not yet open")

		if (this.mode !== 'waitingForStart') return false;

		console.log("TOURNAMENT (" + this.key + "): Registering player");
		if (this.players.indexOf(user) !== -1) return false;
		this.players.push(user);
		return true;
	} 

	this.unregister = function(user) {

		if (this.mode !== 'waitingForStart') return false;

		console.log("TOURNAMENT (" + this.key + "): Unregistering player");
		var i = this.players.indexOf(user);
		if (i === -1) return false;
		this.players.splice(i, 1);
		return true;
	}

	this.init = function() {

		var diff = this.starts_at - Date.now();

		// If less than 15 mins till start, open registration right away
		if (diff < 900000) {
			this.openRegistration();
		} else {
			this.mode = 'waitingForRegistrationToStart';
			this.mediator.pendingTournament(this, diff-900000);
		}
	}

	this.broadcastTimeTillStart = function() {

		console.log("BROADCASTING TIME LEFT TILL START: " + this.getTimeToStart());
		this.broadcastPlayers({tag: 'timeTillStart', data: {key: this.key, time: Math.round(this.getTimeToStart()/1000)}});
	}

	this.openRegistration = function() {

		this.mode = 'waitingForStart';
		var tillStart = this.starts_at - Date.now();
		this.mediator.tournamentRegistrationHasOpened(this, tillStart);
		this.timeTillStartInterval = setInterval(function() {
			console.log("TIME TILL START INFO GOING OUT");
			this.broadcastTimeTillStart();
		}.bind(this), 5000);
	}

	this.getTimeToStart = function() {
		return this.starts_at - Date.now();
	}

	this.listOfPlayers = function() {
		var names = [];
		for (var i = this.players.length - 1; i >= 0; i--) {
			names.push(this.players[i].username);
		};
		return names;
		
	}

	this.init();








}


Tournament.validateStartTime = function(timestamp) {

	var diff = timestamp - Date.now();
	if (diff < 1000 || diff > 900000) return false;
	return true;
}


module.exports = function() {
	return Tournament;
}