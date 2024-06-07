<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call([
            RolesTableSeeder::class,
//             AdminSeeder::class,
//             StreamsTableSeeder::class,
//             BatchesTableSeeder::class,
//             StudentSeeder::class,
            UserSeeder::class,
//             SubjectSeeder::class,
//             GroupSeeder::class,
//             SectionSeeder::class,


        ]);
    }
}
