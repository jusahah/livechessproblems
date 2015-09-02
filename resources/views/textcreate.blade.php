@extends('layout')

@section('content')

<h3>Luo asemia copy-pastella</h3>
<div id="msgArea"></div>
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
        	
          <td class="col-md-2"><input style="font-size: 11px;" type="text" value="{{ old('nimi') }}" name="nimi" class="form-control" id="tehtnimi_1" placeholder="Ruy Lopezin jatko"></td>
          <td class="col-md-6"><input style="font-size: 11px;" type="text" value="{{ old('asema') }}" name="asema" class="form-control" id="tehtasema_1" placeholder="rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6 0 2"></td>
          <td class="col-md-2"><input style="font-size: 11px;" type="text" value="{{ old('ratkaisu') }}" name="ratkaisu" class="form-control" id="tehtratkaisu_1" placeholder="a7-a6"></td>
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

    <button id="luontipainike" class="btn btn-success" type="submit">Luo tehtävä</button>
    <a href="{{url('collections/edit')}}/{{$cid}}" class="btn btn-danger">Palaa kokoelmaan</a>

    </form>

    <script>
    var waitingForResponseFromServer = false;
    var msgCounter = 0;

    $('#tehtavaform').on('submit', function(e) {
    	console.log("Preventing default");
    	e.preventDefault();
    	$('#luontipainike').prop('disabled', true);
    	if (waitingForResponseFromServer) return;

    	
    	var data = {};
    	var table = $('#tehtavaform table');

    	data.nimi = table.find('#tehtnimi_1').val();
    	data.asema = table.find('#tehtasema_1').val();
    	data.ratkaisu = table.find('#tehtratkaisu_1').val();
    	data.vaikeus = table.find('#vaikeus_select').val();

    	sendCreateAway(data);

    })


    function sendCreateAway(data) {
    	console.log("Sending creation data away");
    	waitingForResponseFromServer = true;
		$.ajax({
		  method: "POST",
		  url: "{{url('ajax')}}/collections/{{$cid}}/textcreate",
		  data: { asema: data.asema, nimi: data.nimi, ratkaisu: data.ratkaisu, vaikeus: data.vaikeus}
		})
		  .done(function( msg ) {
		    creationResult('success', 'Creation done successfully!');
		  }).fail(function(msg) {
		  	creationResult('danger', 'Creation failed!');
		  });

    }

    function creationResult(classname, msg) {
    	console.log("Receiving creation result: " + classname);
    	waitingForResponseFromServer = false;
    	$('#msgArea').empty().append('<div class="alert alert-' + classname + '">' + msg + '</div>');
    	$('#luontipainike').prop('disabled', false);
    	msgCounter++;
    	(function(nowCount) {
	    	setTimeout(function() {
	    		if (msgCounter === nowCount) $('#msgArea').empty();
	    	}, 4000);
    	})(msgCounter);
    }


    </script>

	

@stop