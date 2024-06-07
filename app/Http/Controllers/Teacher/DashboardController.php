<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    public function index()
    {
        $assosciatedGroupSubject = Auth::user()->groupSubjects->filter(function($value, $key){
            return $value->deleted_at == null;
        });
        $subjects = [];
        foreach($assosciatedGroupSubject as $groupSubject){
            $teacherSubjectGroup = $groupSubject->pivot->id;
            $subject = Subject::with(['groups'=> function($query) use ($groupSubject){
                                    $query->where('groups.id',$groupSubject->getAttributes()['group_id']);
                                }])
                                ->where('subjects.id',$groupSubject->getAttributes()['subject_id']) //using this method because accessor modifies the attribute
                                ->first();
            $subject->teacherSubjectGroup = $teacherSubjectGroup;
            array_push($subjects,$subject);

        }
        return view('teacher.dashboard.index',compact('subjects'));    
    }
}
