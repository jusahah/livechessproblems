@extends('livelayout')

@section('content')

	<div id="tournamentinfo">

	<h3>Turnauksen rekisteröityminen on sulkeutunut</h3>
	<p>Turnaus on alkanut: <strong> <?php echo date('d.m.Y H:i', strtotime($starts_at)) ;?></strong></p>

	</div>

@stop