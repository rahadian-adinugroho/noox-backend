<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNewsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_read_history', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('news_id')->unsigned();
            $table->dateTime('date');

            $table->primary(['user_id', 'news_id']);

            $table->foreign('user_id')
            ->references('id')->on('user')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('news_id')
            ->references('id')->on('news')
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
        Schema::dropIfExists('user_read_history');
    }
}
