<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class langMiddleware
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
        // if (Session::has('locale')) {
        //     $locale = Session::get('locale', Config::get('app.locale'));
        // } else {
        $local =  session('local');


        \App::setLocale($local);

        return $next($request);
    }
}
