var User = require('./User')();
var Socket = require('./TestSocket')();
var Tournament = require('./Tournament')();

function Server() {

	this.users = {};
	this.tournaments = {};

	this.addUser = function(username) {

		if (this.users.hasOwnProperty(username)) {
			return false;
		}

		this.users[username] = new User(this, username);
		var userSocket = new Socket(this.users[username], 'server');
		this.users[username].setSocket(userSocket);

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