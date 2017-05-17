<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')->unsigned();
            $table->integer('cat_id')->unsigned();
            $table->text('url');
            $table->string('title', 128);
            $table->dateTime('pubtime');
            $table->string('author', 64)->nullable();
            $table->text('content');
            $table->softDeletes();

            $table->foreign('source_id')
            ->references('id')->on('news_sources')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('cat_id')
            ->references('id')->on('news_categories')
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
        Schema::dropIfExists('news');
    }
}
