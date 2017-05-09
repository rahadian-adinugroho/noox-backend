<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\UserReadHistory;

class UserReadHistoryTableSeeder extends Seeder
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
        UserReadHistory::truncate();
        Schema::enableForeignKeyConstraints();

        $entries = [
        ['news_id' => 1, 'user_id' => 1],
        ['news_id' => 1, 'user_id' => 2],
        ['news_id' => 1, 'user_id' => 3],
        ['news_id' => 2, 'user_id' => 1],
        ['news_id' => 2, 'user_id' => 2],
        ['news_id' => 2, 'user_id' => 3],
        ['news_id' => 3, 'user_id' => 3],
        ];
        foreach ($entries as $key => $entry) {
            $data = new UserReadHistory;
            $data->news_id = $entry['news_id'];
            $data->user_id = $entry['user_id'];
            $data->save();
        }
        Model::reguard();
    }
}
