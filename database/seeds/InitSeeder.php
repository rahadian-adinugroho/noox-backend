<?php

use Illuminate\Database\Seeder;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seed will insert essential data into the database such as primary category, report statuses, and achievements list.
     *
     * @return void
     */
    public function run()
    {
        $this->call(NewsCategoryTableSeeder::class);
        // $this->call(NewsSourceTableSeeder::class);
        $this->call(ReportStatusesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
    }
}
