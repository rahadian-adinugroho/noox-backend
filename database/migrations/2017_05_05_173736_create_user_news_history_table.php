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
        Schema::create('user_news_history', function (Blueprint $table) {
            $table->integer('userid')->unsigned();
            $table->integer('newsid')->unsigned();
            $table->dateTime('date');

            $table->primary(['userid', 'newsid']);

            $table->foreign('userid')
            ->references('userid')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('newsid')
            ->references('newsid')->on('news')
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
        Schema::dropIfExists('user_news_history');
    }
}
