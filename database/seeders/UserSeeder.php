<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
                ['name' => 'Niresh Dhakal', 'email' => 'niresh.dhakal@deerwalk.edu.np', 'password' => bcrypt('admin')],
                ['name' => 'DMT', 'email' => 'dmt@deerwalk.edu.np','password' => bcrypt('admin')],
                ['name' => 'Test', 'email' => 'test@deerwalk.edu.np','password' => bcrypt('admin'),'visibility'=>'0'],
        ];

        $user_roles = [
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 1, 'role_id' => 2],
            ['user_id' => 1, 'role_id' => 3],
            ['user_id' => 2, 'role_id' => 1],
            ['user_id' => 2, 'role_id' => 2],
            ['user_id' => 3, 'role_id' => 1],
            ['user_id' => 3, 'role_id' => 2],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        foreach ($user_roles as $user_role) {
            User::find($user_role['user_id'])->roles()->attach($user_role['role_id']);
        }


    }
}
