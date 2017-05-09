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
    	['name' => 'National'],
    	['name' => 'Business'],
    	['name' => 'Crime'],
    	['name' => 'Health'],
    	['name' => 'Lifestyle'],
    	['name' => 'Automotive'],
    	['name' => 'Politic'],
    	['name' => 'Sport'],
    	['name' => 'Technology'],
    	];

    	NewsCategory::insert($entries);
    	Model::reguard();
    }
}
