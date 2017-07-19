<?php

namespace App\Http\Controllers;

class IndexCtrl
{
    /**
     * Show index page
     * @Get("/")
     */
    public function index() {
        return view('welcome');
    }
}