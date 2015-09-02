@extends('layout')

@section('content')

	<div id="tournamentinfo">

	<h3>Turnauksen tiedot</h3>

		<ul class="list-group">
		  <li class="list-group-item"><strong>Nimi</strong> {{$tournament->name}}</li>
		  <li class="list-group-item"><strong>Alkamisaika</strong> {{date('Y-m-d H:i:s',strtotime($tournament->starts_at))}}</li>
		  <li class="list-group-item"><strong>Ratkaisuaika</strong> {{$tournament->ratkaisuaika}} sekuntia</li>
		  <li class="list-group-item"><strong>Tehtäväkokoelma</strong> {{$tournament->collection->name}}</li>

		</ul>
	<hr>




	</div>

@stop