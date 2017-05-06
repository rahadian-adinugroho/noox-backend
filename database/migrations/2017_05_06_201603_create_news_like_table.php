<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_like', function (Blueprint $table) {
            $table->integer('news_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->primary(['news_id', 'user_id']);

            $table->foreign('news_id')
            ->references('id')->on('news')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('user_id')
            ->references('id')->on('user')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_like');
    }
}
