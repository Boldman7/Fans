<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_timelines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timeline_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('type',100);
        });

        Schema::table('saved_timelines', function (Blueprint $table) {
            $table->foreign('timeline_id')->references('id')->on('timelines')
                        ->onDelete('restrict')
                        ->onUpdate('restrict');
        });

        Schema::table('saved_timelines', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                        ->onDelete('restrict')
                        ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saved_timelines', function (Blueprint $table) {
            $table->dropForeign('saved_timelines_timeline_id_foreign');
        });
        Schema::table('saved_timelines', function (Blueprint $table) {
            $table->dropForeign('saved_timelines_user_id_foreign');
        });
        Schema::drop('saved_timelines');
    }
}
