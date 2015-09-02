@extends('layout')

@section('content')

	<div id="creationsuccess">

	<h3>Turnauksesi alkaa: {{$kaunisaika}}</h3>

	<p><span style="color: red; font-size: 11px;">Turnaukseen ilmoittautuminen alkaa 10 minuuttia ja päättyy 1 minuutti ennen turnauksen alkua!</span>
	<p>Kirjoita ylös turnauksen osoite: <span style="color: red;">{{$tournamenturl}}</span></p>

	</div>

@stop

