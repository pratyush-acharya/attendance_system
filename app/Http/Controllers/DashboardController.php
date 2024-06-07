<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Batch;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\GroupSubject;
use App\Models\GroupSubjectTeacher;
use App\Models\Group;
use Carbon\Carbon;
use App\Helpers\DailyAttendanceReportHelper;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select('id','name','email','last_login')->where('visibility','1')->get();
        $batches = Batch::all();
        $piechart_data = $this->piechart();
        $linechart_data = $this->linechart();
        $yearlyMonthAbsentees = $this->yearlyMonthAbsentees();
        return view('admin.dashboard.index')->with(compact('users','piechart_data','linechart_data','yearlyMonthAbsentees','batches'));

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
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function piechart(){
        if(\Request::input('month') != null)
            $month = \Request::input('month');
        else    
            $month = Carbon::now()->format('m');
        // dd(\Request::input('batch'));
        if(\Request::input('batch') != null){
            $batch = \Request::input('batch');
            
            $presentAttendances = Attendance::whereMonth('date',$month)->where('present','!=','0')
                            ->whereHas('student',function($query) use($batch){
                                $query->where('batch_id',$batch);
                            })
                            ->count();
            $absentAttendances = Attendance::whereMonth('date',$month)->where('absent','!=','0')
                                ->whereHas('student',function($query) use($batch){
                                    $query->where('batch_id',$batch);
                                })
                                ->count();
            $leaveAttendances = Attendance::whereMonth('date',$month)->where('leave','!=','0')
                                ->whereHas('student',function($query) use($batch){
                                    $query->where('batch_id',$batch);
                                })
                                ->count();
        }else{
            $presentAttendances = Attendance::whereMonth('date',$month)->where('present','!=','0')->count();
            $absentAttendances = Attendance::whereMonth('date',$month)->where('absent','!=','0')->count();
            $leaveAttendances = Attendance::whereMonth('date',$month)->where('leave','!=','0')->count();
        }

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



    public function linechart(){
        if(\Request::input('month') != null)
            $month = \Request::input('month');
        else    
            $month = Carbon::now()->format('m');
        
        if(\Request::input('batch')!= null){
            $batch = \Request::input('batch');
            
            $attendances = Attendance::whereMonth('date',$month)
                        ->whereHas('student',function($query) use($batch){
                            $query->where('batch_id',$batch);
                        })
                        ->where('present','!=','0')
                        ->get()
                         ->groupBy(function($query){
                            return Carbon::parse($query->date)->format('d');
                        })
                        ->map(function($item){
                            return $item->count();
                        });
        }else{
            $attendances = Attendance::whereMonth('date',$month)->where('present','!=','0')
                        ->orderBy('date','asc')
                        ->get()
                        ->groupBy(function($query){
                            return Carbon::parse($query->date)->format('d');
                        })
                        ->map(function($item){
                            return $item->count();
                        });
        }
        
        $label = [];
        $data = [];
        
        foreach($attendances as $key => $value){
            array_push($label,$key);
            array_push($data,$value);
        }
        return json_encode([$data,$label]);
    }



    public function yearlyMonthAbsentees(){
        // $year = \Request::input('year');
        if(\Request::input('month') != null){
            $year = \Request::input('year');
            $month = \Request::input('month');
        }
        else{
            $year = Carbon::now()->format('Y');
            $month = Carbon::now()->format('m');
        } 
        // dd(\Request::input());       
        if(\Request::input('batch') != null){
            $batch = \Request::input('batch');   

            $groups  = Group::select('id')->where('batch_id',$batch)->get()->toArray();
            $group_ids = array_column($groups,'id');

            $groupSubjects = GroupSubject::select('id')->whereIn('group_id',$group_ids)->get()->toArray();
            $group_subject_ids = array_column($groupSubjects,'id');

            $groupSubjectTeacher = GroupSubjectTeacher::select('id')->whereIn('group_subject_id',$group_subject_ids)->get()->toArray();
            $group_subject_teacher_ids = array_column($groupSubjectTeacher,'id');
            
            $absentees = Attendance::whereYear('date',$year)
                            ->whereMonth('date',$month)
                            ->whereIn('group_subject_teacher_id',$group_subject_teacher_ids)
                            ->where('absent','!=','0')
                            ->count();
        }else{
             $absentees = Attendance::whereYear('date',$year)
                                ->whereMonth('date',$month)
                                ->where('absent','!=','0')->count();
        }
      
        return $absentees;
    }
    
}
