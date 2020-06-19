<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Noox\Models\User;
use Noox\Models\Setting;

class UserTableSeeder extends Seeder
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
        User::truncate();
        DB::table('user_settings')->truncate();
        Schema::enableForeignKeyConstraints();
        
        $users = [
        ['email' => 'jalilboy@jalil.org', 'password' => 'jalils', 'name' => 'Jalil Boy', 'gender' => 'm', 'birthday' => '2017-01-01'],
        ['email' => 'jalilmaster@jalil.org', 'password' => 'jalils', 'name' => 'Jalil Master', 'gender' => 'm', 'birthday' => '2015-01-01'],
        ['email' => 'jalildad@jalil.org', 'password' => 'jalils', 'name' => 'Jalil Dad', 'gender' => 'm', 'birthday' => '2016-01-01'],
        ];

        $initSettings = $this->getInitialSettings();
        foreach ($users as $key => $user) {
        	$entry = new User;
        	$entry->email = $user['email'];
        	$entry->password = bcrypt($user['password']);
        	$entry->name = $user['name'];
        	$entry->gender = $user['gender'];
        	$entry->birthday = $user['birthday'];
          $res = $entry->save();
          $entry->settings()->attach($initSettings);
      }
      Model::reguard();
    }

    /**
     * Get initial settings id and its default value to attach it to the user.
     * 
     * @return array
     */
    protected function getInitialSettings()
    {
        $settings = Setting::get();

        $initSettings = [];
        foreach ($settings as $key => $setting) {
            $initSettings[$setting->id]['value'] =  $setting->default_value;
        }

        return $initSettings;
    }
}
