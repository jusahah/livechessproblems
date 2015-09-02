function Round(tournament, problem, solvetime, roundID, playersCopy) {

	this.roundID = roundID;

	this.tournament = tournament;
	this.problem = problem;
	this.solvetime = solvetime;

	this.hasAnswered = [];
	this.hasNotAnswered = playersCopy;

	this.rightAnswerers = [];
	this.rightAnswerTimes = {};

	this.roundStarted;

	this.init = function() {
		
		this.roundStarted = Date.now();

		setTimeout(function() {
			console.log("ROUND DIES!");
			this.tournament.roundWantsToDie(this);
		}.bind(this), this.solvetime*1000+500);
	}

	this.answerIn = function(answer) {

		console.log("ANSWER ROUNDID: " + answer.roundID);
		console.log("ROUND ID: " + this.roundID);


		if (answer.roundID !== this.roundID) return false;
		var i = this.hasNotAnswered.indexOf(answer.user);
		if (i === -1) return false;

		var answerers = this.hasNotAnswered.splice(i, 1);
		var answerer = answerers[0];
		this.hasAnswered.push(answerer);

		console.log("|||||||||||||||||||||");
		console.log("ANSWER COMPARE");
		console.log("|||||||||||||||||||||");
		console.log(answer.solution + " | " + this.problem.solution);

		if (answer.solution === this.problem.solution) {
			this.rightAnswerers.push(answerer);
			var answerTook = Date.now() - this.roundStarted;
			this.rightAnswerTimes[answerer.username] = answerTook;	
			console.log("Correct answer took: " + answerTook);	
			//console.log("CURRENT CORRECT ANSWERERS");
			//console.log(this.rightAnswerers);
			return 1;
		}
		return 0;
	}

	this.answersGivenSoFar = function() {
		return {tag: 'answersSoFar', data: {roundID: this.roundID, results: this.getResults()}};
	}

	this.getResults = function() {

		var resultSet = {};

		for (var i = this.hasAnswered.length - 1; i >= 0; i--) {
			var answerer = this.hasAnswered[i];

			if (this.rightAnswerers.indexOf(answerer) !== -1) {
				// Correct answer
				console.log("SETTING RESULT CORRECT");
				console.log(answerer);
				console.log(answerer.username);
				//resultSet[answerer.username] = 'correct';
				resultSet[answerer.username] = this.rightAnswerTimes[answerer.username];
			} else {
				// wrong answer
				resultSet[answerer.username] = 'incorrect';
			}
		};

		for (var i = this.hasNotAnswered.length - 1; i >= 0; i--) {
			// Timed out
			resultSet[this.hasNotAnswered[i].username] = 'noanswer';
		};

		console.log("RESULT SET");
		console.log(resultSet);
		return resultSet;
	}

	this.getProblemDataForClients = function() {
		return {roundID: this.roundID, fen: this.problem.position, solvetime: this.solvetime};
	}

	this.init();


}

module.exports = function() {
	return Round;
}