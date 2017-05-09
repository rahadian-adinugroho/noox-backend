<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\NewsCommentLike;

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        NewsCommentLike::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $entries = [
        ['comment_id' => 1, 'user_id' => 2],
        ['comment_id' => 1, 'user_id' => 3],
        ['comment_id' => 2, 'user_id' => 1],
        ['comment_id' => 7, 'user_id' => 1],
        ['comment_id' => 10, 'user_id' => 1],
        ['comment_id' => 11, 'user_id' => 2],
        ];
        NewsCommentLike::insert($entries);
        Model::reguard();
    }
}
