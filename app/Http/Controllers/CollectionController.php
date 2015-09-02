<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Validation\ValidationException;

class CollectionController extends Controller
{

    public function __construct()
    {
        
    }

    public function showCreate() {

        return \View::make('collectionform');
    }


    public function askCollectionKey() {

        return \View::make('askcollectionkeyform');
    }

    public function checkSecretKey() {

        $key = \Input::get('collectionkey');

        try {
            $collection = \App\Collection::where('secret', $key)->firstOrFail();
        } catch(ModelNotFoundException $e) {
            \Session::flash('errorMsg', 'Secret key not found!');
            return \Redirect::back();
        }     

        \Session::put('rightToEdit', $collection->id . "_" . $collection->secret);

        //return $this->showCollectionForEdit();

        return \Redirect::to('collections/edit/' . $collection->id);

        //return \View::make('collectioninfopage')->with('collection', $collection);


    }

    public function showEdit(\Illuminate\Http\Request $request) {

        // Collection existence and edit rights have been checked in middleware

        $collection = \App\Collection::findOrFail($request->route('cid'));

        return \View::make('collectioninfopage')->with('collection', $collection);



    }

    public function destroyProblem(\Illuminate\Http\Request $request) {
        try {
            $collection = \App\Collection::findOrFail($request->route('cid'));
            $problem = \App\Problem::findOrFail($request->route('pid'));           
        } catch(ModelNotFoundException $e) {
            \Session::flash('errorMsg', 'Problem not found!');
            return \Redirect::back();           
        }

        $problem->delete();
        \Session::flash('successMsg', 'Problem deleted');
        return \View::make('collectioninfopage')->with('collection', $collection);
    }

    public function showProblem(\Illuminate\Http\Request $request) {

        try {
            $collection = \App\Collection::findOrFail($request->route('cid'));
            $problem = \App\Problem::findOrFail($request->route('pid'));           
        } catch(ModelNotFoundException $e) {
            \Session::flash('errorMsg', 'Problem not found!');
            return \Redirect::back();           
        }

        return \View::make('probleminfopage')->with('problem', $problem)->with('collection', $collection);

    }


    public function visualCreatePage(\Illuminate\Http\Request $request) {
        return \View::make('visualcreate')->with('cid', $request->route('cid'));
    }

    public function textCreatePage(\Illuminate\Http\Request $request) {

        return \View::make('textcreate')->with('cid', $request->route('cid'));
    }

    public function textCreateReceive(\Illuminate\Http\Request $request) {

        try {
            $this->validate($request, [
                'asema' => 'required|min:16|max:96|checkfen',
                'ratkaisu' => 'required|min:5|max:5|checksolution',
            ]);
        } catch (ValidationException $e) {
            \Session::flash('errorMsg', 'Creation failed - check data');
            return \Redirect::back()->withInput();
        }
        /*
        if (!$this->fenValidation(\Input::get('asema')) || !$this->solutionValidation(\Input::get('ratkaisu'))) {
            \Session::flash('errorMsg', 'Creation failed - check data');
            return \Redirect::back()->withInput();
        }*/

        $this->createProblem($request->route('cid'));
        \Session::flash('successMsg', 'Creation was successfull');
        return \Redirect::back();

    }

    public function createCollection(\Illuminate\Http\Request $request) {

            $this->validate($request, [
                'nimi' => 'required|min:2|max:128',
                'kuvaus' => 'max:2048'
            ]);

            $secret = $this->createNewSecret();

            $collection = new \App\Collection;

            $collection->name = \Input::get('nimi');
            $collection->description = \Input::get('kuvaus');
            $collection->secret = $secret;

            $collection->save();

            return \View::make('collectioncreated')->with('secret', $secret);


    }


    // AJAX STUFF

    public function receiveAjaxProblemCreation(\Illuminate\Http\Request $request) {

       
        $this->validate($request, [
            'asema' => 'required|min:16|max:96|checkfen',
            'ratkaisu' => 'required|min:5|max:5|checksolution',
        ]);
        /*
        if (!$this->fenValidation(\Input::get('asema')) || !$this->solutionValidation(\Input::get('ratkaisu'))) {
            throw new ValidationException('Validation Failed.');
        }
        */

       $this->createProblem($request->route('cid'));

    }

    private function createNewSecret($times = 0) {

        if ($times > 5) {
            return \App::abort(403, 'Could not create secret key - contact administrator');
        }
        $chars = "qwertyuipasdfghjklzxcvbnm12345678900";
        $secret = "";

        for ($i=0; $i < 5; $i++) { 
            $r = rand(0, strlen($chars)-1);
            $secret .= $chars[$r];
        }

        if (\App\Collection::where('secret', $secret)->first()) {
            return $this->createNewSecret($times+1);
        }

        return $secret;
    }

    private function createProblem($cid) {

        $problem = new \App\Problem;

        $problem->name = \Input::get('nimi');
        $problem->position = \Input::get('asema');
        $problem->solution = \Input::get('ratkaisu');
        $problem->difficulty = \Input::get('vaikeus');
        $problem->collection_id = $cid;

        $problem->save();

        return true;

    }



}