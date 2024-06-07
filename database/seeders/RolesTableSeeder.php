<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $roles = ['admin','teacher','student'];
        $roles = ['admin','superadmin','teacher','student'];
        foreach($roles as $role){
            $newRole =  new Role();
            $newRole->roles = $role;
            $newRole->save();
        }
    }
}
