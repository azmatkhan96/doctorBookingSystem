<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $roles = [['roleName' => 'Doctor'], ['roleName' => 'Patient']];
        foreach ($roles as $val) {
            \App\Models\MstRole::insert($val);
        }

        $user = [['name' => 'Admin', 'email' => 'admin@yopmail.com', 'roleID' => 1, 'password' => \Hash::make('12345678')]];
        foreach ($user as $val) {
            \App\Models\User::insert($val);
        }

        $status = [
            ['status' => 'pending'],
            ['status' => 'approve'],
            ['status' => 'cancel'],
            ['status' => 'reject'],
            ['status' => 'postpone'],
        ];

        foreach ($status as $val) {
            \App\Models\MstStatus::insert($val);
        }
    }
}
