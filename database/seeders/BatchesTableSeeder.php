<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $batches = [
                    ['name' => '2021', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'8','stream_id' => 1],
                    ['name' => '2021', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'8','stream_id' => 2],
                    ['name' => '2022', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'6','stream_id' => 1],
                    ['name' => '2022', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'6','stream_id' => 2],
                    ['name' => '2023', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'4','stream_id' => 1],
                    ['name' => '2023', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'4','stream_id' => 2],
                    ['name' => '2024', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'2','stream_id' => 1],
                    ['name' => '2024', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'2','stream_id' => 2],
                    ['name' => '2025', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'1','stream_id' => 1],
                    ['name' => '2025', 'start_date' =>'2022-01-01', 'end_date' =>'2022-06-01','semester'=>'1','stream_id' => 2],
                ];
        foreach ($batches as $batch) {
            Batch::create($batch);
        }
        
        // Batch::factory()->count(10)->create();
    }
}
