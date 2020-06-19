<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserReadHistoryTableSeeder extends Seeder
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
        DB::table('user_read_history')->truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [
        ['news_id' => 1, 'user_id' => 1, 'first_read' => Carbon::now(), 'last_read' => Carbon::now()],
        ['news_id' => 1, 'user_id' => 2, 'first_read' => Carbon::now(), 'last_read' => Carbon::now()],
        ['news_id' => 1, 'user_id' => 3, 'first_read' => Carbon::now(), 'last_read' => Carbon::now()],
        ['news_id' => 2, 'user_id' => 1, 'first_read' => Carbon::now(), 'last_read' => Carbon::now()],
        ['news_id' => 2, 'user_id' => 2, 'first_read' => Carbon::now(), 'last_read' => Carbon::now()],
        ['news_id' => 2, 'user_id' => 3, 'first_read' => Carbon::now(), 'last_read' => Carbon::now()],
        ['news_id' => 3, 'user_id' => 3, 'first_read' => Carbon::now(), 'last_read' => Carbon::now()],
        ];

        DB::table('user_read_history')->insert($entries);
        Model::reguard();
    }
}
