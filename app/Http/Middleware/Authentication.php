<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authentication
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
        if(!Auth::check()) {
            Auth::logout();

            return redirect()->action('UserCtrl@authentication');
        }

        if(Auth::user()->role != "admin") {
            Auth::logout();

            return redirect()->action('UserCtrl@authentication');
        }

        return $next($request);
    }
}
