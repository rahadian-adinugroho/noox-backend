<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsCommentLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_comment_like', function (Blueprint $table) {
            $table->integer('cmid')->unsigned();
            $table->integer('userid')->unsigned();

            $table->primary(['cmid', 'userid']);

            $table->foreign('cmid')
            ->references('cmid')->on('news_comment')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('userid')
            ->references('userid')->on('user')
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
        Schema::dropIfExists('news_comment_like');
    }
}
