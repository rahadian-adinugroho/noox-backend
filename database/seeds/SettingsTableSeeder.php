<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\Setting;

class SettingsTableSeeder extends Seeder
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
        Setting::truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [
        ['key' => 'top_news_notif', 'default_value' => 1],
        ['key' => 'comment_liked_notif', 'default_value' => 1],
        ['key' => 'comment_replied_notif', 'default_value' => 1],
        ['key' => 'report_approved_notif', 'default_value' => 1],
        ];
        Setting::insert($entries);
        Model::reguard();
    }
}
