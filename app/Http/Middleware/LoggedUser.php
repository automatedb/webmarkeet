<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LoggedUser
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
        if(Auth::check()) {
            $action = 'IndexCtrl@index';

            if(Auth::user()->role == 'admin') {
                $action = 'AdminCtrl@index';
            }

            return redirect()->action($action);
        }

        return $next($request);
    }
}
