<?php

namespace App\Console\Commands;

use App\Models\StatsTrading;
use App\Services\MyFxBookService;
use Illuminate\Console\Command;

class MyFxBookScanner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'myfxbook:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $myFxBookService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MyFxBookService $myFxBookService)
    {
        parent::__construct();

        $this->myFxBookService = $myFxBookService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->myFxBookService->auth(config('myfxbook.username'), config('myfxbook.password'));

        $account = $this->myFxBookService->getAccount(501992);

        StatsTrading::updateOrCreate([
            StatsTrading::ACCOUNT_ID => $account['accountId']
        ], [
            StatsTrading::NAME => $account['name'],
            StatsTrading::GAINS => $account['gain'],
            StatsTrading::PROFITS => $account['profit'],
            StatsTrading::WITHDRAWALS => $account['withdrawals'],
            StatsTrading::BALANCE => $account['balance'],
            StatsTrading::DRAWDOWN => $account['drawdown'],
            StatsTrading::PIPS => $account['pips'],
            StatsTrading::PROFIT_FACTOR => $account['profitFactor'],
            StatsTrading::CURRENCY => $account['currency'],
            StatsTrading::DEMO => $account['demo']
        ]);
    }
}
