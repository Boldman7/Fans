<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned();
            $table->integer('user_id')->unsigned();
        });

        Schema::table('saved_posts', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts')
                        ->onDelete('restrict')
                        ->onUpdate('restrict');
        });

        Schema::table('saved_posts', function (Blueprint $table) {
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
        Schema::table('saved_posts', function (Blueprint $table) {
            $table->dropForeign('saved_posts_post_id_foreign');
        });
        Schema::table('saved_posts', function (Blueprint $table) {
            $table->dropForeign('saved_posts_user_id_foreign');
        });
        Schema::drop('saved_posts');
    }
}
