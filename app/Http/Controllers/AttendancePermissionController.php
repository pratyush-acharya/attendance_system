<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendancePermission;
use App\Http\Requests\AttendancePermissionRequest;
use Illuminate\Support\Facades\Log;

class AttendancePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $attendancePermissions = AttendancePermission::all();
        return view('admin.attendance_permission.index')->with(compact('attendancePermissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.attendance_permission.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendancePermissionRequest $request)
    {
        $input = $request->validated();
        try{
            AttendancePermission::create($input);
            return redirect()->route('attendance-permission.list')->with('toast_success','Attendance Permission Submitted Successfully');
        }catch(Exception $e){
            Log::error("Error while submitting attendance permission.". $e);
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
        $attendancePermission = AttendancePermission::find($id);
        return view('admin.attendance_permission.edit')->with(compact('attendancePermission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttendancePermissionRequest $request, $id)
    {
        $input = $request->validated();
        $attendancePermission = AttendancePermission::find($id);
        try{
            $attendancePermission->update($input);
            return redirect()->route('attendance-permission.list')->with('toast_success','Attendance Permission Updated Successfully');
        }catch(Exception $e){
            Log::error("Error while updating attendance permission.". $e);
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
        $attendancePermission = AttendancePermission::find($id);
        try{
            $attendancePermission->delete();
            return redirect()->route('attendance-permission.list')->with('toast_success','Attendance Permission Deleted Successfully');
        }catch(Exception $e){
            Log::error("Error while deleting attendance permission.". $e);
            return back()->with('toast_error','Oops! Error Occured. Please Try Again Later');
        }
    }
}
