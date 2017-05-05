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
            $table->increments('newsid');
            $table->integer('sourceid')->unsigned();
            $table->integer('catid')->unsigned();
            $table->string('title', 128);
            $table->dateTime('pubtime');
            $table->string('author', 64);
            $table->text('content');

            $table->foreign('sourceid')
            ->references('sourceid')->on('news_source')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('catid')
            ->references('catid')->on('news_category')
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
