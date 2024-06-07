<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            ['name'=>'Group1','type'=>'compulsory', 'batch_id'=>3],
            ['name'=>'Group2','type'=>'optional','batch_id'=>4],
        ];

        foreach($groups as $group){
            Group::create($group);
        }
    }
}
