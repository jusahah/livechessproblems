@extends('livelayout')

@section('content')

	<div id="tournamentinfo">

	<h3>Turnauksen rekisteröityminen ei ole vielä auki</h3>
	<p>Turnaus alkaa Suomen aikaa: <strong> <?php echo date('d.m.Y H:i', strtotime($starts_at)) ;?></strong></p><p> Ilmoittautuminen tällä sivulla aukeaa noin 10 minuuttia ennen ja loppuu noin 10 sekuntia ennen turnauksen alkua.</p>

	</div>

@stop