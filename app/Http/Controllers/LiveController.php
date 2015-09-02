<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Validation\ValidationException;

class LiveController extends Controller
{

    public function __construct()
    {
        
    }

    public function askForUsername(\Illuminate\Http\Request $request) {
    	$key = $request->route('key');

    	try {
            $tournament = \App\Tournament::where('key', $key)->firstOrFail();
        } catch(ModelNotFoundException $e) {
            \Session::flash('errorMsg', 'Tournament not found!');
            return \View::make('tournamentnotfound');  
        }

        if ((int)$tournament->loaded_to_server === 0) {
            return \View::make('tournamentnotavailableyet')->with('starts_at', $tournament->starts_at);  
        }

        if (time()+5 > strtotime($tournament->starts_at)) {
            return \View::make('tournamentregistrationclosed')->with('starts_at', $tournament->starts_at); 
        }  




    	return \View::make('askForUsername')->with('tournament', $tournament);
    }
    // Return HTML for client
    public function loadClientApp(\Illuminate\Http\Request $request) {
        // Get tournament key
    	$key = $request->route('key');
        // Make sure tournament key matches to some tournament
    	try {
            $tournament = \App\Tournament::where('key', $key)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            \Session::flash('errorMsg', 'Tournament not found!');
            return \View::make('tournamentnotfound');  
        }  
        // Validate user inputted data
        // Framework will catch any throws higher up in call tree
        $this->validate($request, [
            'username' => 'required|min:2|max:32|alpha_num',
        ]);
        // All is fine
        // Launch client app
        return \View::make('clientApp')->with('username', \Input::get('username'))->with('tournamentkey', $tournament->key);




    }

}