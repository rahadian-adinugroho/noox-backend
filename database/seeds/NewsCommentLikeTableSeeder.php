<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewsCommentLikeTableSeeder extends Seeder
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
        DB::table('news_comment_likes')->truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [
        ['comment_id' => 1, 'user_id' => 2],
        ['comment_id' => 1, 'user_id' => 3],
        ['comment_id' => 2, 'user_id' => 1],
        ['comment_id' => 7, 'user_id' => 1],
        ['comment_id' => 10, 'user_id' => 1],
        ['comment_id' => 11, 'user_id' => 2],
        ];

        DB::table('news_comment_likes')->insert($entries);
        Model::reguard();
    }
}
