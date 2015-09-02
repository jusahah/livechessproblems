@extends('livelayout')

@section('content')

	<div id="collectionform">

	<h3>Olet aloittamassa turnauksen</h3>
	<p>Turnauksen nimi: {{$tournament->name}} </p>
	<p>Turnaus alkaa: <?php echo date('d.m.Y H:i', strtotime($tournament->starts_at));?></p>
	<form method="POST" action="{{url('pelaa')}}/{{$tournament->key}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="text" class="form-control" id="usernameInput" placeholder="Valitse pelaajanimi" name="username">
		<br>
		<button class="btn btn-success" type="submit">Rekisteröidy turnaukseen</button>
	</form>
	</div>

@stop