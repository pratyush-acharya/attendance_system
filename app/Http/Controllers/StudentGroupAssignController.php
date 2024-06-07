<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Group;
use App\Models\Batch;
use App\Models\Stream;
use App\Http\Requests\StudentGroupAssignRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class StudentGroupAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::whereHas('students')->get();
        return view('admin.student_group_assign.index')->with(compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        $students = Student::all();
        $groups = Group::all();
        $batches = Batch::with('stream:id,name')->get();
        $streams = Stream::all();
        return view('admin.student_group_assign.create', compact('students', 'groups', 'batches', 'streams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentGroupAssignRequest $request)
    {
        $input = $request->validated();
        try{
            $group = Group::findOrFail($input['group_id']);
            $group->students()->syncWithoutDetaching($input['students']);
            return redirect()->route('student-group-assign.list')->with('toast_success','Students Assigned to Section Successfully');

        }catch(Exception $e){
            Log::error("Error while assigning student to a group.". $e);
            return back()->with('toast_error','Oops! Error Occured. Please Try Again Later');
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
        $assignedGroup = Group::findOrFail($id);
        $batches = Batch::with('stream:id,name')->get();
        $groups = Group::all();
        $students = Student::all();
        $streams = Stream::all();

        return view('admin.student_group_assign.edit',compact('groups','students','assignedGroup','streams','batches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentGroupAssignRequest $request, $id)
    {
        $input = $request->validated();

        try{
            $group = Group::findOrFail($input['group_id']);
            $group->students()->sync($input['students']);
            return redirect()->route('student-group-assign.list')->with('toast_success','Student Assigned to Section Successfully Updated.');

        }catch(Exception $e){
            Log::error("Error while assigning student to a group.". $e);
            return back()->with('toast_error','Oops! Error Occured. Please Try Again Later');
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
        $group = Group::findOrFail($id);
        try{
            $group->students()->detach();
            return redirect()->route('student-group-assign.list')->with('toast_success', 'Students Assosciated with Section Has Been Deleted.');

        }catch(Exception $e){
            Log::error('Error while deleting assigned student to group'.$e);
            return back()->with('toast_error', 'Oops! Error Occured. Please Try Again Later.');
        }
    }
}
