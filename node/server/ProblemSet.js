function ProblemSet(problems, isRandom) {

	this.problemSet = problems;
	this.isRandom = isRandom;

	this.init = function() {
		if (this.isRandom) {
			this.problemSet = ProblemSet.shuffle(this.problemSet);
		}
	}

	this.numberOfProblemsLeft = function() {
		return this.problemSet.length;
	}

	this.hasProblemsLeft = function() {
		return this.problemSet.length !== 0;
	}

	this.getNextProblem = function() {

		if (this.problemSet.length === 0) return null;
		return this.problemSet.pop();

	}

	this.init();



}

// Helper funs
ProblemSet.shuffle = function(array) {
	    var counter = array.length, temp, index;

	    // While there are elements in the array
	    while (counter > 0) {
	        // Pick a random index
	        index = Math.floor(Math.random() * counter);

	        // Decrease counter by 1
	        counter--;

	        // And swap the last element with it
	        temp = array[counter];
	        array[counter] = array[index];
	        array[index] = temp;
	    }

	    return array;
}

module.exports = function() {
	return ProblemSet;
}