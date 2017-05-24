<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(NewsSourceTableSeeder::class);
        $this->call(NewsCategoryTableSeeder::class);
        $this->call(ReportStatusesTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(UserReadHistoryTableSeeder::class);
        $this->call(NewsCommentTableSeeder::class);
        $this->call(NewsLikeTableSeeder::class);
        $this->call(NewsCommentLikeTableSeeder::class);
        $this->call(ReportsTableSeeder::class);
        $this->call(AchievementsTableSeeder::class);
        $this->call(UserAchievementsTableSeeder::class);
    }
}
