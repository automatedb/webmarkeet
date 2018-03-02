<?php

namespace App\Http\Controllers;

use App\Models\StatsTrading;

class StatsCtrl extends Controller
{
    /**
     * @Get("/stats")
     */
    public function index() {
        $stats = StatsTrading::where(StatsTrading::ACCOUNT_ID, config('myfxbook.accountId'))->first();

        return view('Stats.index', [
            'thumbnail' => config('app.thumbnail'),
            'stats' => $stats
        ]);
    }
}
