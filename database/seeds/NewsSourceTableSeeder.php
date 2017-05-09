<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\NewsSource;

class NewsSourceTableSeeder extends Seeder
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
    	NewsSource::truncate();
    	DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    	$entries = [
    	['source_name' => 'Detik', 'base_url' => 'http://www.detik.com'],
    	['source_name' => 'Kompas', 'base_url' => 'http://www.kompas.com'],
    	['source_name' => 'Liputan 6', 'base_url' => 'http://www.liputan6.com'],
    	];

    	NewsSource::insert($entries);
    	Model::reguard();
    }
}
