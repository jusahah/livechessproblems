<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HasEditCollectionRights
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $cid = $request->route('cid');

        if (\Session::has('rightToEdit')) {

            try {
                $collection = \App\Collection::findOrFail($cid);
            } catch(ModelNotFoundException $e) {
                \Session::flash('errorMsg', 'Collection not found!');
                return \Redirect::route('askcollectionkey');
            }

            

            if (\Session::get('rightToEdit') !== $collection->id . "_" . $collection->secret) {
                \Session::flash('errorMsg', 'Provide collection password in order to edit it!');
                return \Redirect::route('askcollectionkey');
            }

            return $next($request);
        }

        else {

            \Session::flash('errorMsg', 'You have not provided collection password.');
            return \Redirect::route('askcollectionkey');

        }

        
    }
}
