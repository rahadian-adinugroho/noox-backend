<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fb_id', 128)->nullable();
            $table->string('google_id', 128)->nullable();
            $table->string('name', 64);
            $table->string('email', 100)->unique();
            $table->string('password', 128);
            $table->char('gender', 1)->nullable();
            $table->date('birthday')->nullable();
            $table->dateTime('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
