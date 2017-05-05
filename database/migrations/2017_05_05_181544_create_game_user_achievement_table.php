<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameUserAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_user_achievement', function (Blueprint $table) {
            $table->string('acvkey', 32);
            $table->integer('userid')->unsigned();
            $table->dateTime('earndate');

            $table->primary(['acvkey', 'userid']);

            $table->foreign('acvkey')
            ->references('acvkey')->on('game_achievement')
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
        Schema::dropIfExists('game_user_achievement');
    }
}
