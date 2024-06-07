<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Batch;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Group;
use App\Models\User;
use App\Models\Subject;
use App\Exports\UsualAttendanceReportExport;
use App\Models\GroupStudent;
use App\Models\GroupSubject;
use App\Models\GroupSubjectTeacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ReportController extends Controller
{
    public function index()
    {
        $batches =  Batch::all();
        $attendance = Attendance::latest()->first()->student->batch;
        $batch =  Batch::latest()->first();

        $students = Student::where('batch_id', $batch->id)->get()->sortBy('roll_no');

        $subjects = Subject::with(['groups' => function ($query) use ($batch) {
            $query->where('groups.batch_id', $batch->id);
        }])
            ->whereHas('groups', function ($query) use ($batch) {
                $query->where('groups.batch_id', $batch->id);
            })
            ->get()
            ->filter(function ($value, $key) {
                $groupSubjectId = $value->groups->first()->pivot->id;
                $user = User::whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                    $query->where('group_subject_id', $groupSubjectId);
                })->get();
                if (!$user->isEmpty()) {
                    return $value;
                }
            });

//        $groupSubjects = $subjects->map(function ($subject, $key) {
//            return $subject->groups;
//        });
//        dd($groupSubjects);

//        $subjects->map(function ($subject, $key){
//            $subject->
//        });
//        dd($subjects);

//        $subjectTeachers = $subjects->map(function ($subject, $key) {
//            return $subject->groups;
//        });
//        dd($subjectTeachers);
        Log::alert(\request()->ip());

        return view('admin.report.index', compact('students', 'subjects', 'batches'));
    }


    public function search(Request $request)
    {
        $batches =  Batch::all();
        $students = Student::where('batch_id', $request->batch);
        $subjects = Subject::with(['groups' => function ($query) use ($request) {
            $query->where('groups.batch_id', $request->batch);
        }])
            ->whereHas('groups', function ($query) use ($request) {
                $query->where('groups.batch_id', $request->batch);
            });

        $startDate = null;
        $endDate = null;

        if ($request->has('student')) {
            $students = $students->where('roll_no', $request->student);
        }

        if ($request->has('subject')) {
            $subjects = $subjects->where('id', $request->subject);
        }

        if ($request->has('start_date')) {
            $startDate = $request->start_date;
        }

        if ($request->has('end_date')) {
            $endDate = $request->end_date;
        }

        $students = $students->get()
            ->sortBy('roll_no');

        $subjects = $subjects->get()
            ->filter(function ($value, $key) {
                $groupSubjectId = $value->groups->first()->pivot->id;
                $user = User::whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                    $query->where('group_subject_id', $groupSubjectId);
                })->get();
                if (!$user->isEmpty()) {
                    return $value;
                }
            });

        if ($request->has("attendanceFilter") && !is_null($request->attendanceFilter)) {
            $newStudents = collect([]);
            foreach ($students as $student) {
                if ($student->hasBelowAttendanceFilter($subjects, $request->start_date, $request->end_date,$request->attendanceFilter)) {
                    $newStudents->push($student);
                }
            }
            $students = $newStudents;
        }

        if($request->has("attendanceFilterForAllSubject")  && !is_null($request->attendanceFilterForAllSubject)){
            $newStudents = collect([]);
            foreach($students as $student){
                if ($student->hasBelowAttendanceFilterOnAllSubjects($subjects, $request->start_date, $request->end_date,$request->attendanceFilter)) {
                    $newStudents->push($student);
                }
            }

            $students = $newStudents;
        }
        return view('admin.report.index', compact('students', 'subjects', 'batches', 'startDate', 'endDate'));
    }


    public function batchSearch(Request $request)
    {
        $batch = Batch::where('id', $request->batch)->pluck('start_date');
        $students = Student::where('batch_id', $request->batch)->pluck('name', 'roll_no');

        $subjects = Subject::whereHas('groups', function ($query) use ($request) {
            $query->where('groups.batch_id', $request->batch);
        })
            ->with(['groups' => function ($query) use ($request) {
                $query->where('groups.batch_id', $request->batch);
            }])
            ->get()
            ->filter(function ($value, $key) {
                $groupSubjectId = $value->groups->first()->pivot->id;
                $user = User::whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                    $query->where('group_subject_id', $groupSubjectId);
                })->get();
                if (!$user->isEmpty()) {
                    return $value;
                }
            })
            ->pluck('name', 'id');

        return response()->json(['students' => $students, 'subjects' => $subjects, 'start_date' => $batch]);
    }

    public function download(Request $request)
    {
        if ($request->batch == 'false') {
            return back()->with('toast_error', 'Please select at least batch to download the report.');
        }

        $students = Student::where('batch_id', $request->batch);
        $subjects = Subject::with(['groups' => function ($query) use ($request) {
            $query->where('groups.batch_id', $request->batch);
        }])
            ->whereHas('groups', function ($query) use ($request) {
                $query->where('groups.batch_id', $request->batch);
            });
        $startDate = null;
        $endDate = null;

        if ($request->student != 'false') {
            $students = $students->where('roll_no', $request->student);
        }

        if ($request->subject != 'false') {
            $subjects = $subjects->where('id', $request->subject);
        }

        if ($request->start_date != null) {
            $startDate = $request->start_date;
        }

        if ($request->end_date != null) {
            $endDate = $request->end_date;
        }

        $students = $students->get()->sortBy('roll_no');
        $subjects = $subjects->get()
            ->filter(function ($value, $key) {
                $groupSubjectId = $value->groups->first()->pivot->id;
                $user = User::whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                    $query->where('group_subject_id', $groupSubjectId);
                })->get();
                if (!$user->isEmpty()) {
                    return $value;
                }
            });

        return (new UsualAttendanceReportExport($students, $subjects, $startDate, $endDate))->download(time() . '.xlsx');
    }

    public function teacherView(Request $request)
    {
        $batches = $this->getTeacherBatch();
        //taking the first batch
        if ($batches == null){
            return view('teacher.report.index', compact('batches'));
        }
        $batch = $batches[0];
        //get all the students from that batch
        $students = Student::where('batch_id', $batch->id)->get()->sortBy('roll_no');

        //get all the subjects taught in that batch by that teacher
        $subjects = Subject::with(['groups' => function ($query) use ($batch) {
            $query->where('groups.batch_id', $batch->id);
        }])
            ->whereHas('groups', function ($query) use ($batch) {
                $query->where('groups.batch_id', $batch->id);
            })
            ->whereIn('id', function ($query) {
                $query->select('group_subject.subject_id')
                    ->from('group_subject')
                    ->join('group_subject_teacher', 'group_subject.id', '=', 'group_subject_teacher.group_subject_id')
                    ->where('group_subject_teacher.user_id', Auth::user()->id);
            })
            ->get();

        return view('teacher.report.index', compact('students', 'subjects', 'batches'));
    }

    public function teacherBatchSearch(Request $request)
    {
        $batch = Batch::where('id', $request->batch)->pluck('start_date');
        $students = Student::where('batch_id', $request->batch)->pluck('name', 'roll_no');

        $subjects = Subject::whereHas('groups', function ($query) use ($request) {
            $query->where('groups.batch_id', $request->batch);
        })
            ->with(['groups' => function ($query) use ($request) {
                $query->where('groups.batch_id', $request->batch);
            }])
            ->whereIn('id', function ($query) {
                $query->select('group_subject.subject_id')
                    ->from('group_subject')
                    ->join('group_subject_teacher', 'group_subject.id', '=', 'group_subject_teacher.group_subject_id')
                    ->where('group_subject_teacher.user_id', Auth::user()->id);
            })
            ->get()
            ->pluck('name', 'id');

        return response()->json(['students' => $students, 'subjects' => $subjects, 'start_date' => $batch]);
    }



    public function teacherDownload(Request $request)
    {
        if ($request->batch == 'false') {
            return back()->with('toast_error', 'Please select at least batch to download the report.');
        }

        $students = Student::where('batch_id', $request->batch);
        $subjects = Subject::with(['groups' => function ($query) use ($request) {
            $query->where('groups.batch_id', $request->batch);
        }])
            ->whereHas('groups', function ($query) use ($request) {
                $query->where('groups.batch_id', $request->batch);
            });
        $startDate = null;
        $endDate = null;

        if ($request->student != 'false') {
            $students = $students->where('roll_no', $request->student);
        }

        if ($request->subject != 'false') {
            $subjects = $subjects->where('id', $request->subject);
        }

        if ($request->start_date != null) {
            $startDate = $request->start_date;
        }

        if ($request->end_date != null) {
            $endDate = $request->end_date;
        }

        $students = $students->get()->sortBy('roll_no');
        $subjects = $subjects->get()
            ->filter(function ($value, $key) {
                $groupSubjectId = $value->groups->first()->pivot->id;
                $user = User::whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                    $query->where('group_subject_id', $groupSubjectId);
                })->get();
                if (!$user->isEmpty()) {
                    return $value;
                }
            });


        return (new UsualAttendanceReportExport($students, $subjects, $startDate, $endDate))->download(time() . '.xlsx');
    }


    public function teacherSearch(Request $request)
    {
        //first get all the batches
        $batches =  $this->getTeacherBatch();

        $students = Student::where('batch_id', $request->batch);
        $subjects = Subject::with(['groups' => function ($query) use ($request) {
            $query->where('groups.batch_id', $request->batch);
        }])
            ->whereHas('groups', function ($query) use ($request) {
                $query->where('groups.batch_id', $request->batch);
            });
        $startDate = null;
        $endDate = null;
        if ($request->has('student')) {
            $students = $students->where('roll_no', $request->student);
        }

        if ($request->has('subject')) {
            $subjects = $subjects->where('id', $request->subject);
        }

        if ($request->has('start_date')) {
            $startDate = $request->start_date;
        }

        if ($request->has('end_date')) {
            $endDate = $request->end_date;
        }

        $students = $students->get()->sortBy('roll_no');
        $subjects = $subjects->get()
            ->filter(function ($value, $key) {
                $groupSubjectId = $value->groups->first()->pivot->id;
                $user = User::whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                    $query->where('group_subject_id', $groupSubjectId);
                })->get();
                if (!$user->isEmpty()) {
                    return $value;
                }
            });

        return view('teacher.report.index', compact('students', 'subjects', 'batches', 'startDate', 'endDate'));
    }

    private function getTeacherBatch()
    {
        $assosciatedGroupSubjects = Auth::user()->groupSubjects->filter(function($value, $key) {
            return $value->deleted_at == null;
        });
        
        if($assosciatedGroupSubjects->isEmpty()){
            return null;
        }
        //first get all the batches
        $batches = [];
        foreach ($assosciatedGroupSubjects as $assosciatedGroupSubject) {
            // dd($assosciatedGroupSubject->getAttributes()['group_id']);
            $batch = Batch::whereHas('groups', function ($query) use ($assosciatedGroupSubject) {
                $query->where('groups.id', $assosciatedGroupSubject->getAttributes()['group_id']);
            })->first();

            if (!in_array($batch, $batches)) {
                array_push($batches, $batch);
            }
        }
        return $batches;
    }
}
