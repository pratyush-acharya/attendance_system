<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coordinator = new User();
        $coordinator->name = "Coordinator";
        $coordinator->email = "coordinator@deerwalk.edu.np";
        $coordinator->password= bcrypt("coordinator");
        $coordinator->save();
        $coordinator->roles()->attach('3');
    }
}
