<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * @Middleware("web")
 */
class AdminCtrl extends Controller
{
    /**
     * @Get("/app/admin")
     */
    public function index() {
         return response()->view('Admin/index');
    }
}
