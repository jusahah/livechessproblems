function NormalGrader() {

	this.pointsFor = function(result) {

		if (result === 'incorrect') return 0;
		if (result === 'noanswer') return 0;
		if (!isNaN(result)) return 1;
	}

}

module.exports = function() {
	return NormalGrader;
}