@extends('layout')

@section('content')

	<div id="collectionform">

	<h3>Syötä kokoelman tunnus</h3>
	<form method="POST" action="{{route('opencollectionedit')}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="text" class="form-control" id="collectionKeyInput" placeholder="Kokoelman tunnus" name="collectionkey">
		<br>
		<button class="btn btn-success" type="submit">Etsi</button>
	</form>
	</div>

@stop