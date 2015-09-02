@extends('layout')

@section('content')

	<div id="collectioninfo">

	<h3>Luo uusi tehtävä</h3>
		<a href="{{url('collections')}}/{{$collection->id}}/visualcreate" class="btn btn-success">Visuaalinen luonti</a>
		<a href="{{url('collections')}}/{{$collection->id}}/textcreate" class="btn btn-primary">Tekstiluonti</a>
		
	<hr>

	<h3>Kokoelman tiedot</h3>

		<ul class="list-group">
		  <li class="list-group-item"><strong>Nimi</strong> {{$collection->name}}</li>
		  <li class="list-group-item"><strong>Luontipvm</strong> {{$collection->created_at}}</li>
		  <li class="list-group-item"><strong>Tehtäviä yht.</strong> {{$collection->problems->count()}}</li>
		</ul>
	<hr>



	<h3>Kokoelman tehtävät</h3>

	<table class="table table-hover">
      <thead>
        <tr>
          <th>Nimi</th>
          <th>
            Asema
          </th>
          <th>
            Ratkaisu
          </th>
          <th>
            Vaikeus
          </th>
          <th>
            Näytä
          </th>
          <th>
            Poista
          </th>          
        </tr>
      </thead>
      <tbody>


		@foreach ($collection->problems as $problem)
	        <tr>
	          <td>{{$problem->name}}</td>
	          <td>{{$problem->position}}</td>
	          <td>{{$problem->solution}}</td>
	          <td>{{$problem->difficulty}}</td>
	          <td><a type="button" href="{{url('collections')}}/{{$collection->id}}/problems/{{$problem->id}}" class="btn btn-xs btn-primary">Avaa</a></td>
	          <td><a type="button" href="{{url('collections')}}/{{$collection->id}}/problems/delete/{{$problem->id}}" class="btn btn-xs btn-danger">Poista</a></td>
	        </tr>
		@endforeach



      </tbody>


	  
	</table>
	</div>

@stop