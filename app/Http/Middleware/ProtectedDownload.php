<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ProtectedDownload
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

        $user = Auth::user();

        if(!$user->subscribed('monthly') && $user->role != 'admin') {
            $request->session()->flash('alert', [
                'message' => 'Vous devez posséder un abonnment pour télécharger les ressources du site.',
                'type' => 'warning'
            ]);

            Auth::logout();

            return redirect()->action('UserCtrl@authentication');
        }

        return $next($request);
    }
}
