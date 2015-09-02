function TimeGrader() {

	this.pointsFor = function(result) {

		if (result === 'incorrect') return 0;
		if (result === 'noanswer') return 0;
		if (!isNaN(result)) return this.calculateScore(result);
		throw 'Grader got invalid result: ' + result; // Something went wrong
	}

	this.calculateScore = function(timeTaken) {
		return Math.round((15000 - timeTaken) / 1000);
	}


}

module.exports = function() {
	return TimeGrader;
}