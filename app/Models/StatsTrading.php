<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatsTrading extends Model
{
    protected $table = 'stats_trading';

    const ACCOUNT_ID = 'account_id';

    const NAME = 'name';

    const GAINS = 'gains';

    const PROFITS = 'profits';

    const WITHDRAWALS = 'withdrawals';

    const BALANCE = 'balance';

    const DRAWDOWN = 'drawdown';

    const PIPS = 'pips';

    const PROFIT_FACTOR = 'profit_factor';

    const CURRENCY = 'currency';

    const DEMO = 'demo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'account_id', 'gains', 'profits', 'withdrawals', 'balance', 'drawdown', 'pips', 'profit_factor', 'currency', 'demo'];
}
