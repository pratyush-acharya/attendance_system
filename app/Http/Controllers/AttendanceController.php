<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Mail\DailyAttendanceReport;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Group;
use App\Models\GroupSubject;
use App\Models\Holiday;
use App\Models\Student;
use App\Models\Subject;
use App\Models\GroupSubjectTeacher;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Helpers\MailHelper;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $groupSubjectTeacherId = $id;
        
        $assosciatedGroupSubject = GroupSubject::whereHas('users', function($q)use($groupSubjectTeacherId){
                                                    $q->where('group_subject_teacher.id',$groupSubjectTeacherId);
                                                })
                                                ->with(['users'=>function($q)use($groupSubjectTeacherId){
                                                    $q->where('group_subject_teacher.id',$groupSubjectTeacherId);
                                                }])
                                                ->first();

        $subject = Subject::with(['groups'=> function($query) use ($assosciatedGroupSubject){
                                    $query->where('groups.id',$assosciatedGroupSubject->getAttributes()['group_id']);
                                }])
                                ->where('subjects.id',$assosciatedGroupSubject->getAttributes()['subject_id']) //using this method because accessor modifies the attribute
                                ->first();
                                                  

        $students = Student::with(['attendance'=> function($query)use($groupSubjectTeacherId){
                                $query->where('attendances.group_subject_teacher_id',$groupSubjectTeacherId)
                                        ->where('created_at','>', Carbon::now()->subDays(6));
                            }])->whereHas('groups',function($query) use($subject){
                                $query->where('groups.id',$subject->groups->first()->id);
                            })
                            ->orderBy('students.roll_no')
                            ->get();

        $attendanceDates = Attendance::where('group_subject_teacher_id',$groupSubjectTeacherId)
                                        ->where('created_at','>', Carbon::now()->subDays(6))
                                        ->get()
                                        ->groupBy(function($query){
                                            return Carbon::parse($query->created_at)->format('M/d');
                                        })
                                        ->take(5);
        $user_ip = request()->ip();

        return view('teacher.attendance.index',compact('assosciatedGroupSubject','subject','students','groupSubjectTeacherId', 'attendanceDates','user_ip'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendanceRequest $request)
    {

        $input = $request->validated();

        try{
            DB::beginTransaction();

            foreach($input['attendances'] as $attendanceAndRoll)
            {
                $student = Student::where('roll_no',$attendanceAndRoll['rollNo'])->first();
                $attendance = new Attendance();
                $attendance->student_id = $student->id;
                $attendance->group_subject_teacher_id = $input['teacherSubjectGroup'];
                $attendance->present = $attendanceAndRoll['attendanceStatus']['present'];
                $attendance->absent = $attendanceAndRoll['attendanceStatus']['absent'];
                $attendance->leave = $attendanceAndRoll['attendanceStatus']['leave'];
                $attendance->date = date('Y-m-d');
                $attendance->save();
            }
            DB::commit();
            return response()->json(['msg'=>'Attendance Has Been Taken Successfully!',200]);
        }catch(Exception $e){
            DB::rollBack();
            Log::error('Error occured while uploading attendance.' .  $e);
            return response()->json([ 'msg'=>'Oops! Error Occured. Please Try Again Later.',400]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    //Super-Admin Attendance Index Page
    public function list(){
        $attendances = Attendance::where('date',date('Y-m-d'))
                        ->with('student')
                        ->get()
                        ->groupBy('group_subject_teacher_id');

        $groupSubjectTeacherIds = $attendances->keys();

        $assosciatedGroupSubjectTeacher = GroupSubjectTeacher::whereIn('id',$groupSubjectTeacherIds)->get();
        
        $subjects = [];
        foreach($assosciatedGroupSubjectTeacher as $groupSubjectTeacher){
            $subject = Subject::with(['groups'=> function($query) use ($groupSubjectTeacher){
                                    $query->where('groups.id',$groupSubjectTeacher->groupSubject->getAttributes()['group_id']);
                                }])
                                ->where('subjects.id',$groupSubjectTeacher->groupSubject->getAttributes()['subject_id']) //using this method because accessor modifies the attribute
                                ->first();

            $subject->teacherSubjectGroup = $groupSubjectTeacher->id;
        
            
            array_push($subjects,$subject);

        }
        return view('admin.attendance.index',compact('subjects'));    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Super-Admin Attendance Edit Page
    public function edit($id)
    {
        $groupSubjectTeacherId = $id;
        $attendances = Attendance::with('student')
                                    ->where('group_subject_teacher_id',$groupSubjectTeacherId)
                                    ->where('date',date('Y-m-d'))
                                    ->get()
                                    ->sortBy('student.roll_no');
                                    
        $total_classes_of_day = $attendances[0]->present+$attendances[0]->absent+$attendances[0]->leave;
        
        $assosciatedGroupSubject = GroupSubject::whereHas('users', function($q)use($groupSubjectTeacherId){
                                                    $q->where('group_subject_teacher.id',$groupSubjectTeacherId);
                                                })
                                                ->with(['users'=>function($q)use($groupSubjectTeacherId){
                                                    $q->where('group_subject_teacher.id',$groupSubjectTeacherId);
                                                }])
                                                ->first();

        $subject = Subject::with(['groups'=> function($query) use ($assosciatedGroupSubject){
                                    $query->where('groups.id',$assosciatedGroupSubject->getAttributes()['group_id']);
                                }])
                                ->where('subjects.id',$assosciatedGroupSubject->getAttributes()['subject_id']) //using this method because accessor modifies the attribute
                                ->first();
                                                  

        return view('admin.attendance.edit',compact('total_classes_of_day','attendances','assosciatedGroupSubject','subject','groupSubjectTeacherId'));   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttendanceRequest $request)
    {
        $input = $request->validated();
        try{
            DB::beginTransaction();

            foreach($input['attendances'] as $attendanceAndRoll)
            {
                $student = Student::where('roll_no',$attendanceAndRoll['rollNo'])->first();
                $attendance = Attendance::where('group_subject_teacher_id',$input['teacherSubjectGroup'])
                                            ->where('student_id',$student->id)
                                            ->where('date', date('Y-m-d'))
                                            ->first();
                $attendance->present = $attendanceAndRoll['attendanceStatus']['present'];
                $attendance->absent = $attendanceAndRoll['attendanceStatus']['absent'];
                $attendance->leave = $attendanceAndRoll['attendanceStatus']['leave'];
                $attendance->save();              
            }
            DB::commit();
            return response()->json(['msg'=>'Attendance Has Been Updated Successfully!'],200);
        }catch(Exception $e){
            DB::rollBack();
            Log::error('Error occured while updating attendance.' .  $e);
            return response()->json([ 'msg'=>'Oops! Error Occured. Please Try Again Later.'],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sendDailyReportMail(){
        $batches = Batch::all();
        $adminEmail = MailHelper::getAdminEmail();  

        foreach($batches as $batch){
            $batch_holiday = Holiday::where('date',date('Y-m-d'))
                            ->where('batch_id',$batch->id)
                            ->first();

            $students = Student::whereHas('attendance',function($query){    //Students whose attendances were taken today        
                                $query->where('date',date('Y-m-d'));
                            })
                            ->where('batch_id',$batch->id)
                            ->get();

            if($batch->start_date <= date('Y-m-d') && $batch->end_date >= date('Y-m-d')){

                if($batch_holiday != null || !$students->isEmpty()){
                    $mainGroups = Group::where('batch_id',$batch->id)
                    ->where('type','compulsory')
                    ->get();
                    
                    $optionalGroups = Group::where('batch_id',$batch->id)
                    ->where('type','optional')
                    ->get(); 
                    
                    // if($batch->id == 1){
                        // return new DailyAttendanceReport($batch,$mainGroups,$optionalGroups);
                        $mail = Mail::to($adminEmail)
                                    ->cc([
                                        '_academics@deerwalk.edu.np',
                                        'aakancha.thapa@deerwalk.edu.np'
                                    ])
                                    ->bcc([
                                        'pratyush.acharya@deerwalk.edu.np',
                                        'nirnaya.dangol@deerwalk.edu.np',
                                        'nishant.khadka@deerwalk.edu.np'
                                    ])
                                    ->queue(new DailyAttendanceReport($batch,$mainGroups,$optionalGroups));
                    // }
                    
                }
            }
           
        }

        return true;
    }
}
