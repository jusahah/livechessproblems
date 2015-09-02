function IdleState() {

	this.tag = 'idle';
	this.substate = null;

	this.leave = function() {
		console.log("IDLE STATE LEAVING");
	}


}

module.exports = function() {
	return IdleState;
}