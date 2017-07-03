<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NewsLikeTableSeeder extends Seeder
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
        DB::table('news_likes')->truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [
        ['news_id' => 1, 'user_id' => 1, 'liked_at' => Carbon::now()],
        ['news_id' => 1, 'user_id' => 2, 'liked_at' => Carbon::now()],
        ['news_id' => 3, 'user_id' => 3, 'liked_at' => Carbon::now()],
        ];

        DB::table('news_likes')->insert($entries);
        Model::reguard();
    }
}
