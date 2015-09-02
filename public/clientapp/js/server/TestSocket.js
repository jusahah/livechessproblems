function TestSocket(user, side) {

	this.user = user;

	this.endpoint;

	this.setEndpoint = function(socket) {
		this.endpoint = socket;
	}

	this.send = function(packet) {

		setTimeout(function() {
			console.log("SOCKET " + side + ": Sending packet away");
			this.endpoint.receive(packet);
		}. bind(this), Math.random()*1000+200);
	}

	this.receive = function(packet) {

		setTimeout(function() {
			console.log("SOCKET " + side + ": Receiving packet");
			if (!this.user) return false;
			console.log(this.user);
			this.user.msgFromSocket(packet);
		}.bind(this), 0);
	}
}

module.exports = function() {
	return TestSocket;
}