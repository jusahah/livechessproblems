//var WaitingForRound = require('./substates/WaitingForRound');

function PlayingState() {

	this.tag = 'playing';
	this.substates = ['standings', 'solving'];

	this.leave = function() {
		console.log("PLAYING STATE LEAVING");
	}
	this.init = function() {

		this.substate = 'standings';
	}

	this.init();


}

module.exports = function() {
	return PlayingState;
}