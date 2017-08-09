<?php

namespace App\Http\Controllers;

/**
 * @Middleware("web")
 */
class AdminCtrl extends Controller
{
    /**
     * @Get("/admin")
     * @Middleware("admin")
     */
    public function index() {
         return response()->view('Admin/index');
    }
}
