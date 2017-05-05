<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNewsPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_news_preferences', function (Blueprint $table) {
            $table->integer('userid')->unsigned();
            $table->integer('catid')->unsigned();

            $table->primary(['userid', 'catid']);

            $table->foreign('userid')
            ->references('userid')->on('user')
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
        Schema::dropIfExists('user_news_preferences');
    }
}
