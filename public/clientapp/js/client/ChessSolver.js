var Chess = require('./chess').Chess;

function ChessSolver(mediator, $lauta, $countDownEl, chessboard) {

	this.mediator = mediator;
	this.$lauta = $lauta;
	this.chessboard = chessboard;
	this.countDownEl = $countDownEl;

	this.currentLastClicked;

	this.currentPositionObject;

	this.chessLogic;

	this.activeBoard;

	this.solveTime;
	this.problemStartTime;

	this.init = function() {

		this.activeBoard = false;

		this.chessLogic = new Chess();
		this.chessboard.clear();

		//this.countDownEl = $('#countDown');

		this.$lauta.on('click', function(e) {
			if (!this.activeBoard) return false;
			var sq;
			if (e.target.tagName.toUpperCase() === 'IMG') {
				// Piece clicked
				sq = $(e.target).closest('div');
			} else if ($(e.target).hasClass('square-55d63')) {
				// Square itself
				sq = $(e.target);
				
			} else {
				return false;
			}

			this.squareClicked(sq.attr('data-square'));

			
		}.bind(this));
	}

	this.getNewFen = function(from, to) {

		if (this.chessLogic.move({from: from, to: to, promotion: 'q'})) {
			// Move successfull
			return this.chessLogic.fen();
		}

		return false;


	}

	this.resetClock = function() {

		this.stopClock();
		this.countDownEl.empty().append('0.0 sec');

	}

	this.roundOver = function() {
		this.resetClock();
		this.removeHighlights();
		this.currentLastClicked = 0;
		this.activeBoard = false;
	}

	this.squareClicked = function(sqName) {
		if (this.currentLastClicked) {
			if (this.currentLastClicked === sqName) {
				console.log("SQ CLICK REMOVED");
				this.removeHighlights();
				this.currentLastClicked = 0;
				return;
			}
			var last = this.currentLastClicked;
			this.currentLastClicked = 0;
			this.moveHighLight(last, sqName);
			var newFen = this.getNewFen(last, sqName);
			if (newFen) {
				//alert('newFenSuccess');
				this.chessboard.position(newFen.split(" ")[0]);
				this.activeBoard = false;
				this.stopClock();
				return this.mediator.moveMade({from: last, to: sqName});
			} else {
				// Illegal move
				this.currentLastClicked = 0;
				return this.removeHighlights();
			}
			
		} else {
			console.log("SQ CLICK");
			this.sqHighLight(sqName);
			this.currentLastClicked = sqName;
		}
	} 

	this.moveHighLight = function(from, to) {
		this.removeHighlights();
		this.$lauta.find('[data-square="' + from +'"]').addClass('moveHighLight');
		this.$lauta.find('[data-square="' + to +'"]').addClass('moveHighLight');
	}

	this.sqHighLight = function(from) {
		console.log("LIGHTING SQ");
		console.log(this.$lauta.find('[data-square="' + from +'"]'));
		this.$lauta.find('[data-square="' + from +'"]').addClass('sqHighLight');
	}

	this.removeHighlights = function() {
		this.$lauta.find('div.square-55d63').removeClass('sqHighLight').removeClass('moveHighLight');
	}

	this.setupProblem = function(fen, time) {

		this.stopClock();

		this.activeBoard = true;

		this.removeHighlights();

		console.log("SETUPPING PROBLEM");

		var parts = fen.split(" ");
		this.chessboard.position(parts[0]);

		this.currentPositionObject = this.chessboard.position();

		this.chessLogic.load(fen);

		if (parts[1] === 'w') this.chessboard.orientation('white');
		else if (parts[1] === 'b') this.chessboard.orientation('black');

		this.startClock(time);		
	}

	this.startClock = function(time) {

		//alert("Starting clock");
		//alert("SETTING SOLVE TIME: " + time);

		this.solveTime = time*1000;
		this.problemStartTime = Date.now();

		this.clockLoop();


	}

	this.stopClock = function() {

		if (this.clockRunner) {
			clearTimeout(this.clockRunner);
			this.clockRunner = null;
		}
	}

	this.clockLoop = function() {

		this.clockRunner = setTimeout(function() {
			var spend = Date.now() - this.problemStartTime;
			var timeLeft = Math.round((this.solveTime - spend)/100) / 10;
			if (timeLeft < 0) timeLeft = 0;
			console.log(this.problemStartTime + " vs. " + this.solveTime);
			console.log("CLOCK GOING DOWN: " + timeLeft);
			console.log(this.countDownEl);
			this.countDownEl.empty().append(timeLeft.toFixed(1));
			this.clockLoop();
		}.bind(this), 200);
	}

	this.init();
	
}

module.exports = function() {
	return ChessSolver;
}