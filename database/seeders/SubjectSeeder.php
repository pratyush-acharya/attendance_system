<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            ['name'=>'Digital Logic','code'=>'DWIT-027','type'=>'main'],
            ['name'=>'Compiler Design','code'=>'DWIT-033','type'=>'main'],
            ['name'=>'Python','code'=>'DWIT-028','type'=>'credit'],
            ['name'=>'Multimedia Computing','code'=>'DWIT-029','type'=>'elective'],
            ['name'=>'Statistics-I','code'=>'DWIT-030','type'=>'main'],
            ['name'=>'Statistics-II','code'=>'DWIT-031','type'=>'main'],    
            ['name'=>'E-Commerce','code'=>'DWIT-032','type'=>'elective'],

        ];

        foreach($subjects as $subject){
            Subject::create($subject);
            
        }
    }
}
