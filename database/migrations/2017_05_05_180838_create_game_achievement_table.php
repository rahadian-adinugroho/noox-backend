<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_achievement', function (Blueprint $table) {
            $table->string('acvkey', 32);
            $table->string('acvtitle', 64);
            $table->text('acvdesc');
            $table->integer('xpbonus')->unsigned();

            $table->primary('acvkey');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_achievement');
    }
}
