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
            $table->increments('id');
            $table->integer('news_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->dateTime('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->text('content');
            $table->integer('parent_id')->unsigned()->nullable();

            $table->foreign('news_id')
            ->references('id')->on('news')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('user_id')
            ->references('id')->on('user')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('parent_id')
            ->references('id')->on('news_comment')
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
