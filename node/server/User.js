function User(server, username, socket) {

	this.server = server;
	this.username = username;
	this.socket = socket;

	this.currentTournament;
	this.currentMode = 'idle';

	this.getSocket = function() {
		return this.socket;
	}

	this.setSocket = function(socket) {

		this.socket = socket;
	}
	this.msgToSocket = function(packet) {

		this.socket.send(packet);
	}

	this.die = function() {
		if (this.currentTournament) {
			this.currentTournament.userLeft(this);
		}
	}

	this.closeSocket = function() {
		this.socket.disconnect();
	}

	// Gateway
	this.msgFromSocket = function(packet) {

		console.log("SERVER SOCKET MSG");
		console.log(packet);

		if (packet.tag === 'participateToTournament') {
			return this.handleParticipation(packet.data);
		} else if (packet.tag === 'problemAnswer') {
			return this.handleIncomingAnswer(packet.data);
		} else if (packet.tag === 'answer') {
			return this.handleIncomingAnswer(packet.data);
		}
	}

	// Route methods
	this.handleParticipation = function(data) {
		console.log("SERVER USER: Handling participation request: " + data.tournamentKey);
		var tournamentKey = data.tournamentKey;
		var tournament = server.participateInto(tournamentKey, this);
		console.log("Tournament below");
		console.log(tournament);

		if (!tournament) {
			return this.socket.send({
				'tag': 'failureMsg',
				'data': {'msg': 'Tournament participation unsuccessful! Tournament may have started already.'}
			})
		}

		// Participation is successful
		this.currentTournament = tournament;
		this.currentMode = 'tournament';

		console.log("SERVER SENDING PARTICIPATION SUCCESS MSG -> CLIENT");

		return this.socket.send({
			'tag': 'participationSuccess',
			'data': {tournamentKey: tournament.key, starts_in: tournament.getTimeToStart(), username: this.username, playerList: tournament.listOfPlayers()}
		});
	}

	this.handleIncomingAnswer = function(data) {

		console.log("SERVER: INCOMING ANSWER!");
		var tournament = server.getTournament(data.tournamentKey);

		if (!tournament) return this.socket.send({
			'tag': 'failureMsg',
			'data': {'msg': 'Incoming answer ditched - no tournament exists for given key'}
		});

		// Decorate with user
		data.answer.user = this;

		var result = tournament.incomingAnswer(data.answer);	

		if (result !== 0 && result !== 1) return this.socket.send({
			'tag': 'failureMsg',
			'data': {'msg': 'Incoming answer ditched - tournament rejected answer'}
		});

		return this.socket.send({
			'tag' : 'answerResult',
			'data': {tournamentKey: tournament.key, roundID: data.roundID, result: result}
		}); 
	}
	this.removeTournamentLink = function(tournament) {

		if (this.currentTournament === tournament) {
			this.currentTournament = null;
		}

	}

}

module.exports = function() {
	return User;
}