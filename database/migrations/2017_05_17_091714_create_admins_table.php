<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ideally, the role should use a pivot table.
        // The app is still small and to simplify the relation we use a constant instead.
        // 
        // standard admin = 1
        // super admin    = 2
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 100)->unique();
            $table->string('password', 128);
            $table->string('name', 64);
            $table->integer('role')->unsigned();
            $table->string('api_token', 64)->unique();
            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
