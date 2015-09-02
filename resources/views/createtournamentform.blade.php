@extends('layout')

@section('content')

	<div id="collectionform">

	<h3>Luo uusi turnaus</h3>
		<form method="POST" id="tehtavaform" action="{{url('tournaments')}}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
		        <label for="turnausnimi">Nimi</label>
		        <input style="font-size: 11px;" class="form-control" id="nimi_input" type="text" value="{{ old('nimi') }}" name="nimi">
		    </div>

			<div class="form-group">
		        <label for="mustalinna">Alkamisaika <span style="font-size: 11px; color: red;">Syötä muodossa 'dd-mm-yyyy hh:mm'</span></label>
		        <input class="form-control" rows="3" name="alkamisaika" value="{{ old('alkamisaika') }}" placeholder="16-07-2015 09:30"></input>
		    </div>

			<div class="form-group">
		        <label for="mustalinna">Tehtäväkokoelma</label>
		        <input class="form-control" rows="3" name="kokoelmatunnus" value="{{ old('kokoelmatunnus') }}"  placeholder="Syötä kokoelman salasana (secret key)"></input>
		    </div>

			<div class="form-group">
		        <label for="mustalinna">Pistelasku vastausajan mukaan</label>
		        <select class="form-control" id="vaikeus_select" value="{{ old('pistelasku') }}" name="pistelasku">
	          	  <option value="0">Ei</option>
				  <option value="1">Kyllä</option>
			  </select>
		    </div>

			<div class="form-group">
		        <label for="mustalinna">Ratkaisuaika per tehtävä</label>
		        <select class="form-control" id="aika_select" value="{{ old('ratkaisuaika') }}" name="ratkaisuaika">
	          	  <option value="10">10 sekuntia</option>
				  <option value="20">20 sekuntia</option>
				  <option value="30">30 sekuntia</option>
			  </select>
		    </div>

		    <button type="submit" id="tyhja_asema" class="btn btn-success">Luo turnaus</button>
		    </form>
		    <hr>
		    <p style="font-size: 11px; color: red;">Turnaus alkaa itsestään määrittämänäsi ajankohtana. Saat käyttöön kilpailijoiden rekisteröitymisen mahdollistavan linkin.</p>

	</div>

@stop