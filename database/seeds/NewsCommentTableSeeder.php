<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\NewsComment;

class NewsCommentTableSeeder extends Seeder
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
        NewsComment::truncate();
        Schema::enableForeignKeyConstraints();

    	$entries = [
    	[
    	'news_id' => 1,
    	'user_id' => 1,
    	'content' => 'Mantep dah qualcomm!',
    	'parent_id' => null],
    	[
    	'news_id' => 1,
    	'user_id' => 2,
    	'content' => 'Setuju boy!',
    	'parent_id' => 1],
    	[
    	'news_id' => 1,
    	'user_id' => 3,
    	'content' => 'Anak pinter!',
    	'parent_id' => 1],
    	[
    	'news_id' => 1,
    	'user_id' => 1,
    	'content' => 'Lulz!',
    	'parent_id' => 1],
    	[
    	'news_id' => 1,
    	'user_id' => 3,
    	'content' => 'Tapi masih bagusan exynos si!',
    	'parent_id' => null],
    	[
    	'news_id' => 1,
    	'user_id' => 2,
    	'content' => 'Son, I am dissapoint.',
    	'parent_id' => 5],
    	[
    	'news_id' => 2,
    	'user_id' => 3,
    	'content' => 'Tetep aja bodinya kaleng, wakakakakak!',
    	'parent_id' => null],
    	[
    	'news_id' => 2,
    	'user_id' => 1,
    	'content' => 'Yoi beh!',
    	'parent_id' => 7],
    	[
    	'news_id' => 2,
    	'user_id' => 2,
    	'content' => 'Pake carbon fiber donk!',
    	'parent_id' => null],
    	[
    	'news_id' => 2,
    	'user_id' => 3,
    	'content' => 'Harusnya pake baja biar kayak tank. Wkkwkww',
    	'parent_id' => 9],
    	[
    	'news_id' => 2,
    	'user_id' => 1,
    	'content' => 'Masih bagusan suzuki. Lebih tebel bahannya.',
    	'parent_id' => null],
    	];
    	foreach ($entries as $key => $entry) {
    		$data = new NewsComment;
    		$data->news_id = $entry['news_id'];
    		$data->user_id = $entry['user_id'];
    		$data->content = $entry['content'];
    		$data->parent_id = $entry['parent_id'];
    		$data->save();
    	}
    	Model::reguard();
    }
}
