<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsTradingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats_trading', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id');
            $table->string('name');
            $table->double('gains');
            $table->double('profits');
            $table->double('withdrawals');
            $table->double('balance');
            $table->double('drawdown');
            $table->double('pips');
            $table->double('profit_factor');
            $table->string('currency');
            $table->boolean('demo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats_trading');
    }
}
