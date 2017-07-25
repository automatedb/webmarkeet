<?php

namespace App\Http\Controllers;

class IndexCtrl extends Controller
{
    /**
     * Show index page
     * @Get("/")
     */
    public function index() {
        return view('welcome');
    }
}