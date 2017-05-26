<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\NewsLike;

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
        NewsLike::truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [
        ['news_id' => 1, 'user_id' => 1, 'liked_at' => Carbon::now()],
        ['news_id' => 1, 'user_id' => 2, 'liked_at' => Carbon::now()],
        ['news_id' => 3, 'user_id' => 3, 'liked_at' => Carbon::now()],
        ];

        NewsLike::insert($entries);
        Model::reguard();
    }
}
