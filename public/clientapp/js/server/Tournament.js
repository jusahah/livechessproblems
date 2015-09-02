var ProblemSet = require('./ProblemSet')();
var Standings = require('./Standings')();
var Round = require('./Round')();

// Magic numbers
var WAITING_TIME_BETWEEN_ROUNDS = 5000;

function Tournament(server, data) {

	this.mediator = server;

	this.key = data.key;

	this.starts_at = data.starts_at;

	this.players = [];
	this.kibitzers = [];

	this.problemSet = new ProblemSet(data.problems, data.randomOrder);
	this.solveTime = data.solvetime;
	this.timeGrading = data.timegrading;

	this.mode;

	this.currentRound;
	this.roundCounter = 1;

	this.broadcastPlayers = function(msg) {

		for (var i = this.players.length - 1; i >= 0; i--) {
			this.players[i].msgToSocket(msg);
		};

	}

	this.finish = function() {
		console.log('TOURNAMENT: Finishing up...');
		this.mode = 'finished';
		this.broadcastPlayers({tag: 'standings', key: this.key, state: this.mode, standings: this.standings.finalStandings()});
		this.broadcastPlayers({tag: 'finished', data: {key: this.key}});
		// Notify server that we are so done here.
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
		this.broadcastPlayers({tag: 'roundOver', data: {key: this.key, roundID: round.roundID}});
		this.broadcastPlayers({tag: 'standings', key: this.key, state: this.mode, standings: this.standings.overView()});
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

	this.start = function() {

		if (this.players.length === 0) {
			console.log("Tournament starting aborted - no players participating");
			setTimeout(function() {
				this.finish();
			}.bind(this), 0)
			return false;
		}

		this.standings = new Standings(this, this.players);

		this.mode = 'waitingForNextRound';
		this.broadcastPlayers({tag: 'standings', key: this.key, state: this.mode, standings: this.standings.overView()});
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
			this.mode = 'waitingForStart';
			var tillStart = this.starts_at - Date.now();
			this.mediator.tournamentRegistrationHasOpened(this, tillStart);
		} else {
			this.mode = 'waitingForRegistrationToStart';
			this.mediator.pendingTournament(this, diff-900000);
		}
	}

	this.openRegistration = function() {

		this.mode = 'waitingForStart';
		var tillStart = this.starts_at - Date.now();
		this.mediator.tournamentRegistrationHasOpened(this, tillStart);
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