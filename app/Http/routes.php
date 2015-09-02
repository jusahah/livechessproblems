<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'etusivu', function () {
    return view('layout');
}]);

Route::group(['middleware' => ['ajax', 'edit'], 'prefix' => 'ajax'], function() {

	Route::post('collections/{cid}/textcreate', 'CollectionController@receiveAjaxProblemCreation');


});


Route::group(['prefix' => 'collections'], function() {

	// Luontityökalut
	Route::get('{cid}/visualcreate', ['middleware' => 'edit', 'uses' => 'CollectionController@visualCreatePage']);
	Route::get('{cid}/textcreate', ['middleware' => 'edit', 'uses' => 'CollectionController@textCreatePage']);

	Route::post('{cid}/textcreate', ['middleware' => 'edit', 'uses' => 'CollectionController@textCreateReceive']);


	Route::get('edit/{cid}', ['middleware' => 'edit', 'uses' => 'CollectionController@showEdit']);

	Route::get('create',  ['as' => 'createcollectionpage', 'uses' => 'CollectionController@showCreate']);
	Route::get('edit',  ['as' => 'askcollectionkey', 'uses' => 'CollectionController@askCollectionKey']);
	Route::post('edit',  ['as' => 'opencollectionedit', 'uses' => 'CollectionController@checkSecretKey']);


	Route::get('{cid}', ['as' => 'showcollection', 'uses' => 'CollectionController@showCollectionInfo']);
	Route::get('{cid}/problems', 'CollectionController@showProblems');

	Route::get('{cid}/problems/delete/{pid}', ['as' => 'killproblem', 'uses' => 'CollectionController@destroyProblem']);
	Route::get('{cid}/problems/{pid}', ['as' => 'showproblem', 'uses' => 'CollectionController@showProblem']);

	Route::post('/', 'CollectionController@createCollection');
	Route::post('{cid}/problems', 'CollectionController@createProblem');

	Route::delete('{cid}', 'CollectionController@deleteCollection');
	Route::delete('{cid}/problems/{pid}', 'CollectionController@deleteProblem');

});

Route::group(['prefix' => 'tournaments'], function() {
	//GET routes
	Route::get('/', 'TournamentController@showOpenTournaments');
	Route::get('create', ['as' => 'createtournamentpage', 'uses' => 'TournamentController@openCreateTournamentPage']);
	Route::get('/details/{tid}', 'TournamentController@showTournamentDetails');

	Route::get('find', ['as' => 'tournamentsearch', 'uses' => 'TournamentController@openSearchForm']);
	Route::post('find', ['as' => 'opentournamentinfowithkey', 'uses' => 'TournamentController@showTournamentDetailsByKey']);
	
	//POST routes
	Route::post('/', 'TournamentController@createTournament');
	// DELETE routes
	Route::delete('{tid}', 'TournamentController@deleteTournament');
	
});

Route::group(['prefix' => 'pelaa'], function() {
	Route::get('{key}', 'LiveController@askForUsername');
	Route::post('{key}', 'LiveController@loadClientApp');
});

Route::group(['prefix' => 'privateapi', 'middleware' => 'apikey'], function() {
	Route::get('startingtournaments/{apikey}', 'ApiController@fetchStartingTournaments');
});