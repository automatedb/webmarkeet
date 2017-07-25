<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * @Middleware("web")
 */
class AdminCtrl extends Controller
{
    /**
     * @Get("/admin")
     */
    public function index() {
         return response()->view('Admin/index');
    }

    /**
     * @Get("/admin/logout")
     */
    public function logout() {
        Auth::logout();

        return redirect()->action('ContentCtrl@index');
    }
}
