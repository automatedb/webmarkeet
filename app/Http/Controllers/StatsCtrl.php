<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsCtrl extends Controller
{
    /**
     * @Get("/stats")
     */
    public function index() {
        return view('Stats.index', [
            'thumbnail' => config('app.thumbnail')
        ]);
    }
}
