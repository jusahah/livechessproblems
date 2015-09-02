var $ = require('jquery');

var IdleState = require('./states/IdleState')();
var PlayingState = require('./states/PlayingState')();
var KibitzingState = require('./states/KibitzingState')();

var ChessSolver = require('./ChessSolver')();

function SingleClient($clientEl) {

	this.$clientEl = $clientEl;
	this.$lauta = $clientEl.find('#lauta');
	this.notificationArea = $clientEl.find('infoBar');
	this.resultIndicatorEl = $clientEl.find('#resultIndicator');

	this.nextRoundMsg = $('#nextRoundMsg');

	this.socket;

	this.screens = $clientEl.find('.screenPanel');

	this.username;

	this.currentRoundID;

	this.tournamentKey;
	/*
	this.state = new IdleState();

	this.stateResponses = {
		'idle': [],
		'playing': [],
		'kibitzing': []
	};
	*/

	this.chessBoardInstance = ChessBoard('lauta', {
		draggable: false,
		sparePieces: false
	});

	this.chessSolver;

	this.getOwnScore = function(standings) {
		for (var i = standings.length - 1; i >= 0; i--) {
			var standing = standings[i];

			if (standing.username === this.username) return standing.points;
		}

		return '(unknown)';
	}

	this.showStandings = function(standings) {

		this.nextRoundMsg.empty().append('Next round starts in 10 seconds...');

		this.updateToView('standings');
		var standingsEl = this.$clientEl.find('[data-state="standingsUL"]');
		var html = '<table class="table"><thead><tr><th>User</th><th>Score</th><th style="float: right;">Active</th></tr><tbody>';
		var j = 5;

		for (var i = standings.length - 1; i >= 0; i--) {
			if (--j <= 0) break; // No more room for rest of the players 
			var userStanding = standings[i];
			var activeClass = userStanding.active ? 'success' : 'default';
			var activeText = userStanding.active ? 'Active' : 'Has Left';
			html += '<tr><td>' + userStanding.username + '</td><td>' + userStanding.points + '</td><td><span style="float: right;" class="label label-' + activeClass + '">' + activeText +'</span></td></tr>';
		};

		html += '</tbody></table>';
		console.log("UPDATING STANDING HTML ELEMENT");
		html += '<div class="panel panel-default"><div class="panel-heading">My current score</div>';
		html += '<div class="panel-body">' + this.getOwnScore(standings) + '</div></div>';
		standingsEl.empty().append(html);

	}

	this.syncWithTournamentState = function(tournamentState) {



	}

	this.moveMade = function(moveObj) {

		//alert(moveObj.from + " -> " + moveObj.to);
		this.nextRoundMsg.empty().append('Wait for round to end...');
		this.solutionEntered(moveObj.from + "-" + moveObj.to);
	}

	this.init = function() {

		this.chessSolver = new ChessSolver(
			this,
			this.$lauta, 
			$('#countDown'),
			ChessBoard('lauta', {
				draggable: false,
				sparePieces: false,
				moveSpeed: 1
			})
		);



		this.initListeners();

		console.log("CLIENT: Expressing interest participating to tournament " + this.tournamentKey);
		this.socket.send({
			'tag': 'participateToTournament',
			'data': {'tournamentKey': this.tournamentKey}
		});

	}

	this.setServerEndpoint = function(serverSocket) {
		this.socket.connectTo(serverSocket);
	}

	this.msgFromSocket = function(packet) {

		console.log("CLIENT SOCKET MSG");
		console.log(packet);

		console.log(packet.tag + " vs. " + 'participationSuccess');

		if (packet.tag === 'failureMsg') {
			return this.inform('danger', packet.data.msg);
		} else if (packet.tag === 'participationSuccess') {
			console.log("PARTICIPATION SUCCESS HERE");
			//alert("part success");
			return this.participationSuccess(packet.data);
		} else if (packet.tag === 'standings') {
			this.handleIncomingStandings(packet);
		} else if (packet.tag === 'newProblem') {
			this.handleIncomingProblem(packet);
		} else if (packet.tag === 'answerResult') {
			this.handleAnswerResult(packet.data);
		} else if (packet.tag === 'answersSoFar') {
			this.handleAnswersSoFar(packet.data);
		} else if (packet.tag === 'timeTillStart') {
			this.handleTimeTillStart(packet.data);
		} else if (packet.tag === 'roundOver') {
			this.handleRoundOver(packet.data);
		} else if (packet.tag === 'finished') {
			this.handleFinishedTournament(packet.data);
		}
	}

	this.getPrettyTime = function(seconds) {
		if (seconds <= 60) {
			return seconds + " secs";
		}

		var fullMins = Math.floor(seconds / 60);
		var restSecs = seconds - 60 * fullMins;

		return fullMins + " mins " + restSecs + " secs";
	}

	this.resultIndicator = function(classTag) {
		//alert("INDICATOR TO: " + classTag);
		console.log("RESULT INDICATOR");
		console.log(this.resultIndicatorEl);
		this.resultIndicatorEl.removeClass('alert-info alert-danger alert-success alert-inverse').addClass('alert-'+classTag);
	}

	this.finishApp = function() {
		this.nextRoundMsg.empty().append('Tournament has ended!');
		console.log("App has finished");

	}

	// Route handlers

	this.handleFinishedTournament = function(data) {
		if (data.key === this.tournamentKey) {
			this.finishApp();
			//alert("Client received finished tournament notification");

		}
	}

	this.handleRoundOver = function(data) {
		if (this.currentRoundID !== data.roundID) {
			return;
		}
		//alert("ROUND OVER");
		this.chessSolver.roundOver();
	}

	this.handleTimeTillStart = function(data) {

		console.log("CLIENT: RECEIVING TIME TO START NOTIFICATION");
		console.log(data.key + " vs. " + this.tournamentKey);

		if (data.key === this.tournamentKey) {
			console.log("-----------------!!!!!----------------");
			console.log("WAITING FOR TOURNEY START: " + data.time);
			var waitingEl = this.$clientEl.find('[data-state="waiting"]');
			var timeToStartEl = waitingEl.find('#timeToStart');
			timeToStartEl.empty().append('<h3 style="text-align: center;">' + this.getPrettyTime(data.time) + "</h3>");
		}

	}

	this.handleAnswersSoFar = function(data) {
		// If response is too late
		// This checking should probably be done higher up in call tree
		if (data.roundID !== this.currentRoundID) return false;

		//alert("Answers so far received");

		var answersSoFarDiv = this.$clientEl.find('div#answersSoFar');

		//var html = '<ul class="list-group">';
		var html = '<table class="table"><tbody>';


		for (var username in data.results) {
			if (data.results.hasOwnProperty(username)) {

				var labelClass;
				var labelText;

				var r = data.results[username];

				if (!isNaN(r)) {
					labelClass = 'label-success';
					labelText = (Math.round(r/100) / 10).toFixed(1) + ' sec';
				} else if (r === 'incorrect') {
					labelClass = 'label-danger';
					labelText = 'Wrong';
				} else if (r === 'noanswer') {
					labelClass = 'label-default';
					labelText = 'Waiting...';
				} else {
					labelClass = 'label-warning';
					labelText = 'Invalid';
				}

				/*
					html += '<li class="list-group-item">' + username + " | ";
					var r = data.results[username];
					if (r === 'correct') html += 'RIGHT'; 
					else if (r === 'incorrect') html += 'WRONG'; 
					else if (r === 'noanswer') html += 'PONDERING';
					html += '</li>';
					*/
				html += '<tr>';
				html += '<td>' + username + '</td>';
				html += '<td>' + '<span style="float: right;" class="label ' + labelClass + '">' + labelText + '</span></td>';	
				html += '</tr>';
				
			}
		}

		html += '</tbody></table>';
		//alert("Appending latest info who has already given answers");
		answersSoFarDiv.empty().append(html);

	} 

	this.handleAnswerResult = function(data) {

		// If response is too late
		if (data.roundID !== this.currentRoundID) return false;

		console.log("SWITCH TO SHOW ROUND RESULT");

		// We dont want to change view right away, but we also need to be sure
		// that when we do change it, we havent progressed already into next round.
		// If we are, we dont change to result view.
		/*
		(function(currentRoundID) {
			setTimeout(function() {
				
				if (currentRoundID === this.currentRoundID) {
					//this.updateToView('resultOfRound');
					this.updateResultIndicator();
				}
				
			}.bind(this), 1000);	
		}.bind(this))(this.currentRoundID);
		*/

		if (data.result == 1) {
			this.resultIndicator('success');
		} else if (data.result == 0) {
			this.resultIndicator('danger');
		} else {
			this.resultIndicator('inverse');
		}		

		return;

		var resultEl = this.$clientEl.find('[data-state="resultOfRound"]');

		if (data.result == 1) {
			resultEl.empty().append('Your answer was correct!');
		} else if (data.result == 0) {
			resultEl.empty().append('Your answer was incorrect!');
		} else {
			resultEl.empty().append('Your answer was invalid!');
		}
	}

	this.removeOldPartialResults = function() {
		var answersSoFarDiv = this.$clientEl.find('div#answersSoFar');
		answersSoFarDiv.empty().append('(No answers yet given)');
	}

	this.setupBoard = function(fen) {

		var parts = fen.split(" ");
		this.chessBoardInstance.position(parts[0]);

		if (parts[1] === 'w') this.chessBoardInstance.orientation('white');
		else if (parts[1] === 'b') this.chessBoardInstance.orientation('black');
	

	}

	this.handleIncomingProblem = function(problem) {

		// Later on abstract this process of finding screen element out
		this.removeOldPartialResults();

		// Setup problem to board
		//this.setupBoard(problem.fen);

		this.chessSolver.setupProblem(problem.fen, problem.solvetime);
		this.nextRoundMsg.empty().append('Solve!');

		// Show view
		this.updateToView('playing');
		this.resultIndicator('info');
		//var playingEl = this.$clientEl.find('[data-state="playing"]');
		//playingEl.find('#problemFen').empty().append(problem.fen);

		this.currentRoundID = problem.roundID;


	}

	this.handleIncomingStandings = function(packet) {

		if (packet.state === 'waitingForNextRound') {
			this.showStandings(packet.standings);
		}

	}

	this.updateUsername = function(username) {

		//alert("Confirmed username: " + username);

		if (!username) return;

		this.username = username;

	}
 
	this.participationSuccess = function(data) {

		this.updateUsername(data.username);
		this.updateToView('waiting');
		var waitingEl = this.$clientEl.find('[data-state="waiting"]');
		var participationSuccess = waitingEl.find('#participationSuccess');
		participationSuccess.empty().append('<h3 style="text-align: center;">Participation successfull!</h3><p style="text-align: center;">Your username is <strong>' + data.username + '</strong>. </p><p>Waiting for tournament to start. Please do not close browser window / tab. You can use other browser tabs while waiting.</p>');
		
		return;

		if (this.state.tag === 'playing') {
			// Can not start second game while playing one
			this.inform('danger', 'Participation ditched on client-side');
			return false;
		}

		//this.stateChange('playing', data);

	} 

	this.moveToWaitingResult = function() {
		this.updateToView('waiting');
		var waitingEl = this.$clientEl.find('[data-state="waiting"]');
		waitingEl.empty().append('<h3 style="text-align: center;">Waiting for response from server...</h3>');
	}

	this.solutionEntered = function(solution) {

		console.log("SENDING AWAY SOLUTION FROM CLIENT: " + solution + " for round #: " + this.currentRoundID);

		this.socket.send({
			tag: 'answer',
			data: {
				roundID: this.currentRoundID,
				tournamentKey: this.tournamentKey,
				answer: {
					solution: solution,
					key: this.tournamentKey,
					roundID: this.currentRoundID,
				}
			}


		});

		//this.moveToWaitingResult();

	}

	this.stateChange = function(to, data) {
		var newState;
		if (to === 'playing') {
			newState = new PlayingState(data);
		} else if (to === 'kibitzing') {
			newState = new KibitzingState(data);
		} else if (to === 'idle') {
			newState = new IdleState(data);
		}

		if (newState) {
			this.state.leave();
			this.state = newState;
			// Broadcast of state change
			this.updateToView();
		}
	}

	this.updateToView = function(view, subview) {

		console.log("Updating views");
		/*
		var state = this.state;
		var substate = this.state.substate;
		*/
		this.screens.each(function() {
			$(this).hide();
		});

		var toBeShown = this.$clientEl.find('[data-state="' + view + '"]');
		toBeShown.show();
		/*
		if (substate) {
			var subScreens = toBeShown.find('[data-state="' + substate + '"]');
			subScreens.each(function() {
				$(this).show();
			});
		}
		*/


	}

	this.inform = function(tag, msg) {
		//$('body').empty().append('Client app has failed. This may be due tournament key being invalid, tournament having been played already or some undefined behaviour by server.');
		console.log(msg);

	}

	this.initListeners = function() {

		var button = this.$clientEl.find('button.submitSolution');
		button.on('click', function() {
			//alert("click");
			var solution = this.$clientEl.find('input.testSolutionInput').val();
			this.$clientEl.find('input.testSolutionInput').empty();
			this.solutionEntered(solution);
		}.bind(this));
	}
}

module.exports = function() {
	return SingleClient; // jo
}