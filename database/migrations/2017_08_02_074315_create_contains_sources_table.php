<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainsSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contains_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id');
            $table->integer('content_id');
            $table->timestamps();

            $table->foreign('source_id')->references('id')->on('sources');
            $table->foreign('content_id')->references('id')->on('contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contains_sources');
    }
}
