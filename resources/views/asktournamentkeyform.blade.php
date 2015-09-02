@extends('layout')

@section('content')

	<div id="tournamentform">

	<h3>Syötä turnauksen tunnus</h3>
	<form method="POST" action="{{route('opentournamentinfowithkey')}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="text" class="form-control" id="tournamentKeyInput" placeholder="Turnauksen tunnus" name="tournamentkey">
		<br>
		<button class="btn btn-success" type="submit">Etsi</button>
	</form>
	</div>

@stop