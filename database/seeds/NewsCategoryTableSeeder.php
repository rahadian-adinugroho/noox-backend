<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\NewsCategory;

class NewsCategoryTableSeeder extends Seeder
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
        NewsCategory::truncate();
        Schema::enableForeignKeyConstraints();

    	$entries = [
    	['name' => 'national'],
    	['name' => 'business'],
    	['name' => 'crime'],
    	['name' => 'health'],
    	['name' => 'lifestyle'],
    	['name' => 'automotive'],
    	['name' => 'politic'],
    	['name' => 'sport'],
    	['name' => 'technology'],
    	];

    	NewsCategory::insert($entries);
    	Model::reguard();
    }
}
