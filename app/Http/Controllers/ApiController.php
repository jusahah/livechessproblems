<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Validation\ValidationException;

class ApiController extends Controller
{

    public function __construct()
    {
        
    }

    public function fetchStartingTournaments() {
    	$dateTimeNow = date('Y-m-d H:i:s', strtotime('+5 seconds'));
    	$dateTimeSoon = date('Y-m-d H:i:s', strtotime('+600 seconds'));

    	//echo $dateTimeNow . "<br>";

    	$tournaments = \App\Tournament::where('loaded_to_server', 0)
    	->where('starts_at', '>', $dateTimeNow)
    	->where('starts_at', '<', $dateTimeSoon)
    	->with('collection.problems')
    	->get();
 		
 		// Set back info for each that we have loaded them
 		// This should probably be done in a background job
 		$tournaments->each(function($tournament) {
 			$tournament->loaded_to_server = 1;
 			$tournament->save();

 		});
 		return response()->json($tournaments); 
    }
}