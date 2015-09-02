@extends('layout')

@section('content')

	<?php 

		$siirtovuoro = explode(" ", $problem->position)[1] === 'w' ? 'Valkea' : 'Musta';

	?>

	<div id="probleminfo">

	<h3>Tehtävän tiedot</h3>

		<ul class="list-group">
		  <li class="list-group-item"><strong>Nimi</strong> {{$problem->name}}</li>
		  <li class="list-group-item"><strong>Luontipvm</strong> {{$problem->created_at}}</li>
		  <li class="list-group-item"><strong>Asema</strong> {{$problem->position}}</li>
		  <li class="list-group-item"><strong>Ratkaisu</strong> {{$problem->solution}}</li>
		  <li class="list-group-item"><strong>Vaikeus</strong> {{$problem->difficulty}}</li>
		  <li class="list-group-item"><strong>Siirtovuoro</strong> {{$siirtovuoro}}</li>
		  <li class="list-group-item"><a href="{{url('collections/edit')}}/{{$collection->id}}" class="btn btn-success">Palaa kokoelmaan</a></li>
		</ul>

	<h3>Asema laudalla</h3>

		<div id="asemalaudalla_holder">
			<p class="nojs_lauta" style="color: red;">Lauta vaatii Javascript-tuen</p>
			<div id="shakkilauta" style="width: 400px; height: 400px;"></div>
		</div>
	</div>

	<script>
	var board1 = ChessBoard('shakkilauta', "<?php echo explode(' ', $problem->position)[0];?>");

	</script>

@stop

