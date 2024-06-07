<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class StudentViewController extends Controller
{
    public function dashboard(Request $request)
    { 
        $student = Student::where('id', Auth::guard('student')->user()->id)->first(); 
        $subjects = $this->allSubjects($student)
                            ->filter(function($value,$key){
                                foreach($value->groups as $group){
                                    $groupSubjectId = $group->pivot->id;
                                    $user = User::whereHas('groupSubjects', function($query) use($groupSubjectId){
                                                        $query->where('group_subject_id', $groupSubjectId);
                                                    })->get();

                                    if(!$user->isEmpty())
                                    {
                                        return $value;
                                    }
                                }
                            });
                            
        $piechart_data = $this->piechart($request);
        $userIp = request()->ip();
        Log::alert($userIp);
        return view('student.dashboard',compact('subjects','student', 'piechart_data'));
    }

    public function search(Request $request)
    {
        $student = Student::where('id', Auth::guard('student')->id())->first(); 

        $subjects = $this->allSubjects($student);

        $piechart_data = $this->piechart($request);
        $requestedSubject ='';

        if($request->has('subject'))
        {
            $requestedSubject = $request->subject;
            if($requestedSubject != "all_subjects")
                $subjects = $subjects->where('id',$requestedSubject);
        }
        
        $subjects = $subjects->filter(function($value,$key){
                                $groupSubjectId = $value->groups->first()->pivot->id;
                                $user = User::whereHas('groupSubjects', function($query) use($groupSubjectId){
                                                    $query->where('group_subject_id', $groupSubjectId);
                                                })->get();
                                if(!$user->isEmpty())
                                {
                                    return $value;
                                }
                            });
        
        $allSubjects = $this->allSubjects($student);

        return view('student.dashboard', compact('subjects', 'student','allSubjects', 'piechart_data','requestedSubject'));
    }

    private function allSubjects(Student $student)
    {
        $subjects=collect([]);

        foreach($student->groups as $group){
            $subjects = $subjects->merge(Subject::with(['groups'=> function($query)use($student,$group){
                                $query->where('groups.batch_id',$student->batch_id)
                                    ->where('groups.id', $group->id);
                            }])
                            ->whereHas('groups', function($query)use($student,$group){
                                $query->where('groups.batch_id',$student->batch_id)
                                        ->where('groups.id', $group->id);
                            })
                            ->get()
                        );
        }
      
        return $subjects;
    }

    public function piechart(Request $request){

        $student = Student::where('id', Auth::guard('student')->user()->id)->first(); 

        if($request->input('month') != null){

            $month = $request->input('month');
        }
        else{
            $month = Carbon::now()->format('m');
        }    
 
           

        $presentAttendances = Attendance::whereMonth('date',$month)->where('present','!=','0')->where('student_id', $student->id)->count();
        $absentAttendances = Attendance::whereMonth('date',$month)->where('absent','!=','0')->where('student_id', $student->id)->count();
        $leaveAttendances = Attendance::whereMonth('date',$month)->where('leave','!=','0')->where('student_id', $student->id)->count();

        // dd($presentAttendances);
        
        $label = [
            'present',
            'absent',
            'leave' 
        ];
        $data = [
            $presentAttendances,
            $absentAttendances,
            $leaveAttendances
        ];
        return json_encode([$data,$label]);
    }
}
