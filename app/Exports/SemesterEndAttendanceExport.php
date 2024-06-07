<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class SemesterEndAttendanceExport implements FromView
{
    use Exportable;
    private $batchId;

    public function __construct(int $batchId)
    {
        $this->batchId = $batchId;
    }

    public function view(): View
    {
        $batchId = $this->batchId;
        $students = Student::where('batch_id',$batchId)->get()->sortBy('roll_no');

        $subjects = Subject::with(['groups'=> function($query)use($batchId){
                                $query->withTrashed()->where('groups.batch_id',$batchId);
                            }])
                            ->whereHas('groups', function($query)use($batchId){
                                $query->withTrashed()->where('groups.batch_id',$batchId);
                            })
                            ->get()
                            ->filter(function($value,$key){
                                $groupSubjectId = $value->groups->first()->pivot->id;
                                $user = User::whereHas('groupSubjects', function($query) use($groupSubjectId){
                                                    $query->where('group_subject_id', $groupSubjectId);
                                                })->get();
                                if(!$user->isEmpty())
                                {
                                    return $value;
                                }

                            }); 
        return view('layouts.exports.semesterEndReport', compact('students','subjects'));
    }
}
