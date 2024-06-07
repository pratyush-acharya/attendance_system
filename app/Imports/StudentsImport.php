<?php

namespace App\Imports;

use App\Models\Batch;
use App\Models\Stream;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;

class StudentsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    use Importable;
    
    
    /**
    * @param array $row
    */
    public function collection(Collection $rows){
        foreach ($rows as $row){
            $stream = Stream::where('name','like', '%'.$row['stream'].'%')->first();
            $batch = Batch::where([
                                ['name', $row['batch']],
                                ['stream_id', $stream->id]
                            ])->first();

            $group = Group::where([
                            ['name',$row['section']],
                            ['batch_id',$batch->id]
                        ])->first();

            unset($row['batch']);
            unset($row['stream']);
            unset($row['section']);
            
            if($group != null)
                $row['section_id'] = $group->id;
            if($batch != null)
                $row['batch_id'] = $batch->id;

            Validator::make($row->toArray(), [
                'name' => ['required'],
                'email' => ['required'],
                'roll_number' => ['required'],
                'section_id' => ['required','exists:groups,id'],        //section == group
                'batch_id' => ['exists:batches,id'],
            ])->validate();

            $student = Student::withTrashed()
                                ->updateorCreate([
                                    'roll_no'=> $row['roll_number'],
                                    'email'=> $row['email']
                                ]
                                ,[
                                    'name' => $row['name'],
                                    'email' => $row['email'],
                                    'roll_no' => $row['roll_number'],
                                    'batch_id' => $row['batch_id']
                                ]);
            
            $student->groups()->sync($row['section_id']);
        }
    }

}
