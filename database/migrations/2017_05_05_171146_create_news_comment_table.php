<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_comment', function (Blueprint $table) {
            $table->increments('cmid');
            $table->integer('newsid')->unsigned();
            $table->integer('userid')->unsigned();
            $table->dateTime('created');
            $table->text('content');
            $table->integer('refcmid')->unsigned();

            $table->foreign('newsid')
            ->references('newsid')->on('news')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('userid')
            ->references('userid')->on('user')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('refcmid')
            ->references('cmid')->on('news_comment')
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
        Schema::dropIfExists('news_comment');
    }
}
