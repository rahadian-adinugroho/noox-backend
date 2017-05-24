<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserAchievementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        Schema::disableForeignKeyConstraints();
        DB::table('user_achievements')->truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [
        ['achievement_id' => 1, 'user_id' => 1, 'earn_date' => Carbon::now()],
        ['achievement_id' => 6, 'user_id' => 1, 'earn_date' => Carbon::now()],
        ['achievement_id' => 11, 'user_id' => 1, 'earn_date' => Carbon::now()],
        ['achievement_id' => 1, 'user_id' => 2, 'earn_date' => Carbon::now()],
        ['achievement_id' => 1, 'user_id' => 3, 'earn_date' => Carbon::now()],
        ];

        DB::table('user_achievements')->insert($entries);
        Model::reguard();
    }
}
