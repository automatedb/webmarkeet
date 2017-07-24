<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCtrl extends Controller
{
    /**
     * @Middleware("web")
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
