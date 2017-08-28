<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyContainsSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contains_sources', function (Blueprint $table) {
            $table->integer('content_id')->nullable()->change();
            $table->integer('chapter_id')->nullable();

            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contains_sources', function(Blueprint $table) {
            $table->dropForeign('contains_sources_chapter_id_foreign');
            $table->dropColumn('chapter_id');
        });
    }
}
