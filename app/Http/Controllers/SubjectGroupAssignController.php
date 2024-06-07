<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectGroupAssignRequest;
use App\Models\Group;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectGroupAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::whereHas('subjects')->get();
        return view('admin.subject_group_assign.index',compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        $subjects = Subject::all();

        return view('admin.subject_group_assign.create',compact('groups','subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectGroupAssignRequest $request)
    {
       $request->validated();
        try{
            $group = Group::findOrFail($request->group);
            $group->subjects()->syncWithoutDetaching($request->subjects);
            return redirect()->route('subject_group_assign.list')->with('toast_success','Subject Assigned to Section Successfully');

        }catch(Exception $e){
            Log::error("Error while assigning subject to a group.". $e);
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
        $groups = Group::all();
        $subjects = Subject::all();

        return view('admin.subject_group_assign.edit',compact('groups','subjects','assignedGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectGroupAssignRequest $request, $id)
    {
        $request->validated();
        try{
            $group = Group::findOrFail($request->group);
            $group->subjects()->sync($request->subjects);
            return redirect()->route('subject_group_assign.list')->with('toast_success','Subject Assigned to Section Successfully Updated.');

        }catch(Exception $e){
            Log::error("Error while assigning subject to a group.". $e);
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
            $group->subjects()->detach();
            return redirect()->route('subject_group_assign.list')->with('toast_success', 'Subject Assosciated with Section Has Been Deleted.');

        }catch(Exception $e){
            Log::error('Error while deleting assigned subject to group'.$e);
            return back()->with('toast_error', 'Oops! Error Occured. Please Try Again Later.');
        }
    }
}
