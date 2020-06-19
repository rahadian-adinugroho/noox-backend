<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\ReportStatus;

class ReportStatusesTableSeeder extends Seeder
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
        ReportStatus::truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [
        ['name' => 'open'],
        ['name' => 'investigating'],
        ['name' => 'solved'],
        ['name' => 'approved'],
        ['name' => 'closed'],
        ];

        ReportStatus::insert($entries);
        Model::reguard();
    }
}
