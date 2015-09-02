function Standings(tournament, players, grader) {

	this.answerValuations = {
		'correct': 1,
		'incorrect': -1,
		'noanswer': 0
	}

	this.tournament = tournament;
	this.players = players;
	this.grader = grader;

	this.points = {};

	this.init = function() {

		for (var i = this.players.length - 1; i >= 0; i--) {
			this.points[this.players[i].username] = 0;
		};


	}

	this.overView = function(currentPlayerList) {

		// Get still active usernames

		var activeUsernames = [];
		for (var i = currentPlayerList.length - 1; i >= 0; i--) {
			activeUsernames.push(currentPlayerList[i].username);
		};

		var ranking = [];

		for (var username in this.points) {
			if (this.points.hasOwnProperty(username)) {
				ranking.push({
					username: username, 
					points: this.points[username], 
					active: activeUsernames.indexOf(username) !== -1
				});
			}
		}

		ranking.sort(function(a, b) {
			return a.points - b.points;
		});
		console.log("CURRENT RANKING");
		console.log(ranking);

		return ranking;

	}

	this.finalStandings = function() {
		return this.overView();
	}

	this.addNewResults = function(results) {

		/* results = {'matti': 'correct', 'pekka': 'noanswer', ...} */

		for (username in results) {
			if (results.hasOwnProperty(username)) {
				if (this.points.hasOwnProperty(username)) {
					var r = results[username];
					this.points[username] += this.grader.pointsFor(r);
				}
			}
		}
		/*
		for (var i = results.length - 1; i >= 0; i--) {
			var result = results[i];
			if (this.points.hasOwnProperty(result.username)) {
				this.points[result.username] += result.roundPoints;
			}
		};
		*/
		return true;

	}

	this.init();


}


module.exports = function() {
	return Standings;
}