<?php

namespace App\Http\Middleware;

use App\Models\User;
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
        if(!Auth::check() && strpos($request->path(), 'admin') > -1) {
            return redirect()->action('UserCtrl@authentication');
        }

        if(Auth::check()) {
            /** @var User */
            $user = Auth::user();

            if(strpos($request->path(), 'authentication') > -1) {
                $redirect = 'UserCtrl@index';

                if($user->role === "admin") {
                    $redirect = 'AdminCtrl@index';
                }

                return redirect()->action($redirect);
            }

            if(strpos($request->path(), 'admin') > -1 && $user->role === "customer") {
                return redirect()->action('UserCtrl@index');
            }
        }

        return $next($request);
    }
}
