@extends('layout')

@section('content')


<div id="msgArea"></div>

<div class="row">

	<div class="col-md-6">
			<ul id="mustat" style="list-style: none;">
				<li id="bK_li">
				<img class="selected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/bK.png" data-piece="bK" style="width: 49px; height: 49px;">
				</li>
				<li id="bQ_li">
				<img class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/bQ.png" data-piece="bQ" style="width: 49px; height: 49px;">
				</li>
				<li id="bR_li">
				<img class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/bR.png" data-piece="bR" style="width: 49px; height: 49px;">
				</li>
				<li id="bB_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/bB.png" data-piece="bB" style="width: 49px; height: 49px;">
				</li>
				<li id="bN_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/bN.png" data-piece="bN" style="width: 49px; height: 49px;">
				</li>	
				<li id="bP_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/bP.png" data-piece="bP" style="width: 49px; height: 49px;">
				</li>					
			</ul>
			<div id="luontilauta" style="width: 456px;"></div>
			<ul id="valkeat" style="list-style: none;">
				<li id="wK_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/wK.png" data-piece="wK" style="width: 49px; height: 49px;">
				</li>
				<li id="wQ_li">
				<img class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/wQ.png" data-piece="wQ" style="width: 49px; height: 49px;">
				</li>
				<li id="wR_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/wR.png" data-piece="wR" style="width: 49px; height: 49px;">
				</li>
				<li id="wB_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/wB.png" data-piece="wB" style="width: 49px; height: 49px;">
				</li>
				<li id="wN_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/wN.png" data-piece="wN" style="width: 49px; height: 49px;">
				</li>	
				<li id="wP_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/wP.png" data-piece="wP" style="width: 49px; height: 49px;">
				</li>	
				<li id="empty_li">
				<img  class="notSelected" src="http://localhost/chessproblem/laravel/public/img/chesspieces/wikipedia/empty.png" data-piece="empty" style="width: 49px; height: 49px;">
				</li>					
			</ul>

			
	</div>
	<div class="col-md-6">
		<div id="lisatiedot" style="width: 100%;">
			<div class="form-group">
		        <label for="siirtovuoro">Siirtovuoro</label>
		        <select class="form-control" id="siirtovuoroselect">
				  <option value="w">Valkea</option>
				  <option value="b">Musta</option>

				</select>
		    </div>

			<div class="form-group">
		        <label for="valkealinna">Valkean linnoitus</label>
		        <select class="form-control" id="valkealinnaselect">
				  <option value="-">Ei linnoitusoikeutta</option>
				  <option value="Q">Daamisivusta</option>
				  <option value="K">Kuningassivusta</option>
				  <option value="KQ">Molemmat</option>
				</select>
		    </div>
			<div class="form-group">
		        <label for="mustalinna">Mustan linnoitus</label>
		        <select class="form-control" id="mustalinnaselect">
				  <option value="-">Ei linnoitusoikeutta</option>
				  <option value="q">Daamisivusta</option>
				  <option value="k">Kuningassivusta</option>
				  <option value="kq">Molemmat</option>
				</select>
		    </div>
		    <button id="tyhja_asema" class="btn btn-danger">Tyhjennä lauta</button>
		      
		</div>	
	</div>	

</div>

<hr>



<form method="POST" id="tehtavaform" action="{{url('collections')}}/{{$cid}}/textcreate">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
 <table class="table table-bordered table-striped" id="syottotable">
      <thead>
        <tr>

          <th>
            Nimi 
          </th>
          <th>
            Asema <span style="color:red; font-size: 10px;">pakollinen</span>
          </th>
          <th>
            Ratkaisu <span style="color:red; font-size: 10px;">pakollinen</span>
          </th>
          <th>
            Vaikeus
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
        	
          <td class="col-md-2"><input style="font-size: 11px;" class="form-control" type="text" value="{{ old('nimi') }}" name="nimi"></td>
          <input type="hidden" name="asema" id="hidden_asema" value="">
          <td class="col-md-6" id="asemateksti">-</td>
          <td class="col-md-2" id="ratkaisuteksti"><input style="font-size: 11px;" class="form-control" id="ratkaisuinput" type="text" value="{{ old('ratkaisu') }}" name="ratkaisu"></td>
          <td class="col-md-2">
	          <select class="form-control" id="vaikeus_select" value="{{ old('vaikeus') }}" name="vaikeus">
	          		<option value="0"></option>
				  <option value="1">Helppo</option>
				  <option value="2">Keskitaso</option>
				  <option value="3">Vaikea</option>
			  </select>
          	

          </td>
        </tr>
        <tr>

      </tbody>
    </table>

    <button id="luontipainike" class="btn btn-primary" type="submit" disabled>Luo tehtävä</button>
    <a href="{{url('collections/edit')}}/{{$cid}}" class="btn btn-danger">Palaa kokoelmaan</a>

    </form>

<script>
var mustatEl = $('#mustat');
var valkeatEl = $('#valkeat');
var asemavalmis = $('#asemavalmis');
var lautaEl = $('#luontilauta');

var siirtoVuoroSelect = $('#siirtovuoroselect');
var mustaLinnaSelect = $('#mustalinnaselect');
var valkeaLinnaSelect = $('#valkealinnaselect');

siirtoVuoroSelect.on('change', function() {
	reWriteFen();
});
mustaLinnaSelect.on('change', function() {
	reWriteFen();
});
valkeaLinnaSelect.on('change', function() {
	reWriteFen();
});

var currentlySelected = 'bK';
var currentPosition = {};
var currentFen = '';


function linnat() {

	var valkea = valkeaLinnaSelect.val(); 
	var musta = mustaLinnaSelect.val();

	if (valkea === '-' && musta === '-') return '-';
	if (valkea === '-') valkea = '';
	if (musta === '-') musta = '';
	return valkea + musta;
}




function reWriteFen() {

	var fen = lauta.fen() + " " + siirtoVuoroSelect.val() + " " + linnat() + " - 0 1";
	currentFen = fen;
	positionUpdated(fen);
}

function resetAllImgs() {
	console.log("Resetting all piece imgs");

	var mustatImgs = mustatEl.find('img');
	var valkeatImgs = valkeatEl.find('img');

	mustatImgs.each(function() {
		$(this).removeClass('selected').addClass('notSelected');
	});
	valkeatImgs.each(function() {
		$(this).removeClass('selected').addClass('notSelected');
	});

}

function resetAllRoundeds() {

	var sqs = lautaEl.find('div.square-55d63');

	sqs.each(function() {
		$(this).removeClass('roundedSq').addClass('notRoundedSq');
	}); 
}

function changeCurrentlySelected(to) {

	resetAllImgs();
	var li;
	if (to[0] === 'b') {
		li = mustatEl.find('#' + to + "_li");
	} else if (to[0] === 'w') {
		li = valkeatEl.find('#' + to + "_li");
	} else {
		li = valkeatEl.find('#empty_li');
	}

	console.log(li);

	li.find('img').removeClass('notSelected').addClass('selected');

	if (to === 'empty') to = '';
	currentlySelected = to;


}



function positionUpdated(fen) {

	$('input#hidden_asema').val(fen);
	$('#asemateksti').empty().append(fen);
}

var lauta = ChessBoard('luontilauta', {
	draggable: false,
	//dropOffBoard: 'trash',
	sparePieces: false,
	
});

$('#tyhja_asema').on('click', function() {
	currentPosition = {};
	lauta.position(currentPosition, false);
	reWriteFen();
});

$('#ratkaisuinput').on('change', function() {

	var answer = $('#ratkaisuinput').val();
	resetAllRoundeds();
	var parts = answer.split("-");

	var foundSqs = 0;
	var disabled = true;

	if (parts.length === 2) {

		if (parts[0].length === 2) {
			var sq = lautaEl.find('[data-square="' + parts[0] + '"]');
			if (sq.length) {
				foundSqs++;
				sq.removeClass('notRoundedSq').addClass('roundedSq');
			}
		}

		if (parts[1].length === 2) {
			var sq = lautaEl.find('[data-square="' + parts[1] + '"]');
			if (sq.length) {
				foundSqs++;
				sq.removeClass('notRoundedSq').addClass('roundedSq');
			}
		}	

		if (parts[0] === parts[1]) foundSqs--;
		

	}

	if (foundSqs === 2) {
		disabled = false;
	} 

	$('#luontipainike').prop('disabled', disabled);

});


lautaEl.on('click', function(e) {
	var sq;
	console.log(e);
	if (e.target.tagName.toUpperCase() === 'IMG') {
		sq = $(e.target).closest('div');
	} else {
		sq = $(e.target);
	}

	var sqName = sq.attr('data-square');
	console.log(sqName + " | " + currentlySelected);
	if (currentlySelected === '') {
		if (currentPosition.hasOwnProperty(sqName)) {
			currentPosition[sqName] = null;
			delete currentPosition[sqName];
		}
	} else {
		currentPosition[sqName] = currentlySelected;
	}
	
	console.log(currentPosition);
	lauta.position(currentPosition, false);
	reWriteFen();
	//positionUpdated(lauta.fen());
});

mustatEl.on('click', function(e) {
	if (e.target.tagName.toUpperCase() === 'IMG') {
		var pieceCode = $(e.target).attr('data-piece');
		console.log("PIECE CODE: " + pieceCode);
		changeCurrentlySelected(pieceCode);

	}
});

valkeatEl.on('click', function(e) {
	if (e.target.tagName.toUpperCase() === 'IMG') {
		var pieceCode = $(e.target).attr('data-piece');
		console.log("PIECE CODE: " + pieceCode);
		changeCurrentlySelected(pieceCode);
	}
});

reWriteFen();


</script>

	

@stop