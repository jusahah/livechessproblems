<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Validation\ValidationException;

class TournamentController extends Controller
{

    public function __construct()
    {
        
    }

    public function openCreateTournamentPage() {

    	return \View::make('createtournamentform');
    }

    public function openSearchForm() {

    	return \View::make('asktournamentkeyform');
    }

    public function createTournament(\Illuminate\Http\Request $request) {

    	$this->validate($request, [
            'nimi' => 'required|min:2|max:96',
            'alkamisaika' => 'required|date|after:now',
            'kokoelmatunnus' => 'required|checkSecret',
        ]);

        $pistevaihtoehdot = ['0', '1'];
        $aikavaihtoehdot  = ['10', '20', '30'];

        $piste = \Input::get('pistelasku');
        $ratkaika = \Input::get('ratkaisuaika');
        $aika = \Input::get('alkamisaika');

        if (!in_array($piste, $pistevaihtoehdot)) $piste = '0';
        if (!in_array($ratkaika, $aikavaihtoehdot)) $ratkaika = '10';

        $key = $this->addTournamentToDB(\Input::get('nimi'), $aika, \Input::get('kokoelmatunnus'), $piste, $ratkaika);
       	
       	return \View::make('tournamentcreationsuccess')
    		->with('tournamenturl', 'www.shakkiratkonta.fi/ratko/' . $key)
    		->with('alkaa', $aika)
    		->with('kaunisaika', date('d.M.Y H:i',strtotime($aika)));

    }

    public function showTournamentDetailsByKey(\Illuminate\Http\Request $request) {

     	try {
            $tournament = \App\Tournament::where('key', \Input::get('tournamentkey'))->firstOrFail();           
        } catch(ModelNotFoundException $e) {
            \Session::flash('errorMsg', 'Tournament not found by provided key!');
            return \Redirect::back();           
        }

        return \Redirect::to('tournaments/details/' . $tournament->id);

    }

    public function showTournamentDetails(\Illuminate\Http\Request $request) {

    	$tournamentID = $request->route('tid');

    	 try {
            $tournament = \App\Tournament::findOrFail($tournamentID);           
        } catch(ModelNotFoundException $e) {
            \Session::flash('errorMsg', 'Tournament not found!');
            return \Redirect::back();           
        }

        return \View::make('tournamentinfopage')->with('tournament', $tournament);

    	
    }


    private function addTournamentToDB($nimi, $aika, $secret, $pistelasku, $ratkaisuaika) {

        try {
            $collection = \App\Collection::where('secret', $secret)->firstOrFail();
        } catch(ModelNotFoundException $e) {
            \Session::flash('errorMsg', 'Secret key not found!');
            return \Redirect::back();
        }
       
    	$tournament = new \App\Tournament;

    	$key = $this->createNewSecret();

    	$tournament->key = $key;
    	$tournament->name = $nimi;
    	$tournament->starts_at = date('Y-m-d H:i:s', strtotime($aika));
    	$tournament->pistelasku = $pistelasku;
    	$tournament->ratkaisuaika = $ratkaisuaika;
    	$tournament->loaded_to_server = 0;
    	$tournament->collection_id = $collection->id;

    	$tournament->save();

    	return $key;

    }

    private function createNewSecret($times = 0) {

        if ($times > 5) {
            return \App::abort(403, 'Could not create tournament secret key - contact administrator');
        }
        $chars = "qwertyuipasdfghjklzxcvbnm12345678900";
        $secret = "";

        for ($i=0; $i < 4; $i++) { 
            $r = rand(0, strlen($chars)-1);
            $secret .= $chars[$r];
        }

        if (\App\Tournament::where('key', $secret)->first()) {
            return $this->createNewSecret($times+1);
        }

        return $secret;
    }

}