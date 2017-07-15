<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\Admin;

class AdminsTableSeeder extends Seeder
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
        Admin::truncate();
        Schema::enableForeignKeyConstraints();
        
        $admins = [
        ['email' => 'rahaxtreme@gmail.com', 'password' => 'raharaha', 'name' => 'Rahadian Adinugroho', 'role' => 2],
        ['email' => 'jamesw@james.com', 'password' => 'james', 'name' => 'James Walker', 'role' => 1],
        ];

        foreach ($admins as $key => $admin) {
            $entry = new Admin;
            $entry->email = $admin['email'];
            $entry->password = bcrypt($admin['password']);
            $entry->name = $admin['name'];
            $entry->role = $admin['role'];
          $entry->save();
      }
      Model::reguard();
    }
}
