<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stream;

class StreamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $streams = [ 'CSIT', 'BCA' ];

        foreach($streams as $stream){

            $newStream = new Stream();
            $newStream->name = $stream;
            $newStream->save();
            
        }
    }
}
