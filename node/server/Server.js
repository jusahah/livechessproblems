var User = require('./User')();
var Socket = require('./ServerSocket')();
var Tournament = require('./Tournament')();

function Server() {

	this.users = {};
	this.tournaments = {};

	this.addUser = function(username, socket) {

		if (this.users.hasOwnProperty(username)) {
			var randomNum = Math.floor(Math.random() * 10);
			var nextUsernameTry = username + "_" + randomNum.toString();
			return this.addUser(nextUsernameTry, socket); 	
		}

		this.users[username] = new User(this, username);
		var userSocket = new Socket(this.users[username], 'server', socket);
		this.users[username].setSocket(userSocket);

		return this.users[username];

	}

	this.socketFor = function(username) {

		if (!this.users.hasOwnProperty(username)) {
			return false;
		}

		return this.users[username].getSocket();

	}

	this.getTournament = function(key) {
		return this.tournaments[key];
	}

	this.addTournament = function(tournamentData) {

		if (!tournamentData.key || this.tournaments.hasOwnProperty[tournamentData.key]) {
			console.log("ERROR IN SERVER: Tournament creation failed with key " + tournamentData.key);
			return false;
		}

		if (!Tournament.validateStartTime(tournamentData.starts_at)) {
			console.log("ERROR IN SERVER: Tournament start time not valid: " + tournamentData.starts_at);
			return false;
		}
			
		console.log("SERVER: Tournament added: " + tournamentData.key);
		this.tournaments[tournamentData.key] = new Tournament(this, tournamentData);

	}

	this.participateInto = function(tournamentKey, user) {

		console.log("PARTICIPATING TO: " + tournamentKey);

		if (!this.tournaments.hasOwnProperty(tournamentKey)) return null;

		var tournament = this.tournaments[tournamentKey];

		if (tournament.register(user)) {
			return tournament;

		}

		return null;


	}

	this.closePlayers = function(players) {
		for (var i = players.length - 1; i >= 0; i--) {
			var user = players[i];
			user.closeSocket();
		};
	}

	this.tournamentDone = function(tournament) {
		// Log results to DB here
		if (this.tournaments.hasOwnProperty(tournament.key)) {
			this.closePlayers(tournament.players);
			console.log("Deleting ended tournament");
			this.tournaments[tournament.key] = null;
			delete this.tournaments[tournament.key];

		}

	}

	this.pendingTournament = function(tournament, pendingTime) {
		// All pendings come through here.
		// Later abstract out to own component (Timer or something)
		setTimeout(function() {
			tournament.openRegistration();
		}, pendingTime+1000);
	}

	this.tournamentRegistrationHasOpened = function(tournament, tillStart) {
		console.log("SERVER: Tournament registration open notification received from tournament");
		setTimeout(function() {
			tournament.start();
		}, tillStart);
	}
}

module.exports = function() {
	return Server;
}