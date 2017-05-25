<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\Report;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seed depends on 'users' and 'news' table. Seed them first before executing this seed.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        Schema::disableForeignKeyConstraints();
        Report::truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [

        ['reporter_id' => 1, 'content' => 'Tukang ngespam.', 'status_id' => 2, 'reportable_id' => 3, 'reportable_type' => 'user'],
        ['reporter_id' => 3, 'content' => 'Sok pinter.', 'status_id' => 4, 'reportable_id' => 1, 'reportable_type' => 'user'],
        ['reporter_id' => 2, 'content' => 'Ternyata hoax.', 'status_id' => 1, 'reportable_id' => 3, 'reportable_type' => 'news'],
        ['reporter_id' => 2, 'content' => 'Hobinya ngespam.', 'status_id' => 1, 'reportable_id' => 3, 'reportable_type' => 'user'],
        ['reporter_id' => 1, 'content' => 'Kurang relevan.', 'status_id' => 3, 'reportable_id' => 4, 'reportable_type' => 'news'],
        ];
        
        foreach ($entries as $key => $entry) {
            $data                  = new Report;
            $data->reporter_id     = $entry['reporter_id'];
            $data->content         = $entry['content'];
            $data->status_id       = $entry['status_id'];
            $data->reportable_id   = $entry['reportable_id'];
            $data->reportable_type = $entry['reportable_type'];
            $data->save();
        }
        Model::reguard();
    }
}
