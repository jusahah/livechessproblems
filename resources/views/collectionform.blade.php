@extends('layout')

@section('content')

	<div id="collectionform">

	<h3>Luo uusi kokoelma</h3>
		<form method="POST" id="tehtavaform" action="{{url('collections')}}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
		        <label for="siirtovuoro">Nimi</label>
		        <input style="font-size: 11px;" class="form-control" id="nimi_input" type="text" value="{{ old('nimi') }}" name="nimi">
		    </div>

			<div class="form-group">
		        <label for="mustalinna">Kuvaus</label>
		        <textarea class="form-control" rows="3" name="kuvaus"></textarea>
		    </div>
		    <button type="submit" id="tyhja_asema" class="btn btn-success">Luo kokoelma</button>
		    <hr>
		    <p style="font-size: 11px; color: red;">Luonnin jälkeen saat käyttöön kokoelman hallinnoinnissa käytettävän salasanan</p>

	</div>

@stop