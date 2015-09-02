function ServerSocket(user, side, realSocket) {

	this.user = user;
	this.realSocket = realSocket;

	this.endpoint;

	this.setEndpoint = function(socket) {
		this.endpoint = socket;
	}

	this.disconnect = function() {
		this.realSocket.disconnect();
	}

	this.send = function(packet) {

		this.realSocket.emit('msg', packet);

		/*

		setTimeout(function() {
			console.log("SOCKET " + side + ": Sending packet away");
			this.endpoint.receive(packet);
		}. bind(this), Math.random()*1000+200);
		*/
	}

	this.receiveJoin = function(data) {
		alert("Server has accepted you");
	}

	this.receive = function(packet) {

		setTimeout(function() {
			console.log("SOCKET " + side + ": Receiving packet");
			if (!this.user) return false;
			console.log(this.user);
			this.user.msgFromSocket(packet);
		}.bind(this), 0);
	}

	this.init = function() {

	  this.realSocket.on('msg', function (data) {
	    this.receive(data);
	  }.bind(this));
	  /*
	  this.realSocket.on('join', function (data) {
	    this.receiveJoin(data);
	  }.bind(this));
	  */

	   //this.realSocket.emit('join', {tournamentkey: this.user.tournamentKey, username: this.user.username});
	}

	this.init();
}

module.exports = function() {
	return ServerSocket;
}