function KibitzingState() {

	this.tag = 'kibitzing';
	this.substate = null;

	this.leave = function() {
		console.log("KIBITZING STATE LEAVING");
	}


}

module.exports = function() {
	return KibitzingState;
}