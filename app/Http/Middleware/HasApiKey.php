<?php

namespace App\Http\Middleware;

use Closure;

class HasApiKey
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
        $apikey = $request->route('apikey');
        if ($apikey !== \Config::get('app.apikey')) return App::abort(403, 'Unauthorized action');
        return $next($request);
    }
}
