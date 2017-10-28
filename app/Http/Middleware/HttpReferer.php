<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class HttpReferer
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
        $referer = $request->headers->get('referer');

        if(strstr($referer,'facebook') !== false) {
            View::share('referer', 'facebook');
        }

        return $next($request);
    }
}
