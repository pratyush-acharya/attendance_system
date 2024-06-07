<?php

namespace App\Http\Controllers;

use App\Exports\SemesterEndAttendanceExport;
use App\Models\Stream;
use App\Models\Batch;
use App\Http\Requests\BatchRequest;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\GroupSubject;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batches = Batch::with('stream:id,name')
                            ->get();
        return view('admin.batch.index')->with(compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $streams = Stream::select('id','name')->get();

        return view('admin.batch.create')->with(compact('streams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BatchRequest $request)
    {
        $input = $request->validated();
        try{
            $batch = Batch::create($input);
            return redirect()->route('batch.list')->with('toast_success','Batch Created Successfully.');
        }catch( Exception $e){
            Log::error("Error occurred while creating batch. Error report". $e);
            return redirect()->route('batch.list')->with('toast_error', 'Oops! Error Occured! Please Try Again Later!');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $batch = Batch::findOrFail($id);

        $streams = Stream::select('id','name')->get();
        return view('admin.batch.edit')->with(compact('batch','streams'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BatchRequest $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $input = $request->validated();
        $batch->update($input);
        return redirect()->route('batch.list')->with('toast_success','Batch Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $batch = Batch::findOrFail($id);
            //delete everything assosciated to the batch
            $groups = Group::withTrashed()->where('batch_id',$id)->get();
            //now delete all the assosciations
            foreach($groups as $group)
            {

                //delete all group student 
                DB::table('group_student')->where('group_id',$group->id)->delete();
                
                //delete all group_subject
                $groupSubjects = GroupSubject::withTrashed()->where('group_id', $group->id)->get();
    
                foreach($groupSubjects as $groupSubject)
                {
                    //fetach all group subject teacher assosciated with group subject
                    $groupSubjectTeachers = DB::table('group_subject_teacher')->where('group_subject_id', $groupSubject->id)->get();
    
                    foreach($groupSubjectTeachers as $groupSubjectTeacher)
                    {
                        //delete all attendance assosciated with group subject teacher
                        Attendance::withTrashed()->where('group_subject_teacher_id', $groupSubjectTeacher->id)->forceDelete();
                    }
    
                    //now delete all the group subject teacher assosciated with group subject
                    DB::table('group_subject_teacher')->where('group_subject_id', $groupSubject->id)->delete();
                    $groupSubject->delete();
                }
                //delete group
                $group->forceDelete();
            }
            Student::where('batch_id', $id)->delete();
            $batch->delete();
            return response()->json(['msg'=>'Batch Deleted Successfully.', 'status'=>'true'],200); 
        }
        catch(\Illuminate\Database\QueryException $e){
            Log::error("Error while deleting group. Error report: ". $e);
            if($e->getCode() == "23000"){
                return response()->json(['msg'=>'Batch cannot be deleted.Delete all the assosciated students and groups first.','status'=>'false'],200); 
            }
                return response()->json(['msg'=>'Oops! Error Occured. Please Try Again Later.','status'=>'false'],200); 
            }
    }

    public function semesterEndReport($id)
    {
        $batch = Batch::where('id', $id)->first();
        return (new SemesterEndAttendanceExport($id))->download($batch->name.'-'.$batch->stream->name.'.xlsx');
    }

    public function forceDelete($id)
    {
        $batch = Batch::where('id', $id)->first();
        $groups = Group::withTrashed()->where('batch_id',$id)->get();
        //now delete all the assosciations
        foreach($groups as $group)
        {

            //delete all group student 
            DB::table('group_student')->where('group_id',$group->id)->delete();
            
            //delete all group_subject
            $groupSubjects = GroupSubject::withTrashed()->where('group_id', $group->id)->get();

            foreach($groupSubjects as $groupSubject)
            {
                //fetach all group subject teacher assosciated with group subject
                $groupSubjectTeachers = DB::table('group_subject_teacher')->where('group_subject_id', $groupSubject->id)->get();

                foreach($groupSubjectTeachers as $groupSubjectTeacher)
                {
                    //delete all attendance assosciated with group subject teacher
                    Attendance::withTrashed()->where('group_subject_teacher_id', $groupSubjectTeacher->id)->forceDelete();
                }

                //now delete all the group subject teacher assosciated with group subject
                DB::table('group_subject_teacher')->where('group_subject_id', $groupSubject->id)->delete();
                $groupSubject->delete();
            }
            //delete group
            $group->forceDelete();
        }

        if($batch->semester < 8 ){

            $batch->semester = $batch->semester + 1;
            $batch->start_date = date('Y-m-d');
            $batch->end_date = date('Y-m-d', strtotime("+6 months", strtotime(date('Y-m-d'))));
            $batch->save();
            return response()->json(['msg'=>'All assosciations with this batch deleted successfully']); 
        }else{
            Student::where('batch_id', $id)->delete();
            $batch->delete();
            return response()->json(['msg'=>'All assosciations with this batch along with the batch deleted successfully']); 
        }

    }
}
