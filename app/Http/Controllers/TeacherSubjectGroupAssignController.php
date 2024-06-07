<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherSubjectGroupAssignRequest;
use App\Models\Group;
use App\Models\GroupSubject;
use App\Models\Student;
use App\Models\Subject;
use App\Models\GroupSubjectTeacher;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherSubjectGroupAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = User::with(['groupSubjects'=>function($query){
                        $query->where('group_subject.deleted_at', null);
                    }])
                    ->whereHas('groupSubjects', function($query){
                        $query->where('group_subject_teacher.deleted_at', null);
                    })
                    ->get();
        return view('admin.teacher_subject_group_assign.index',compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachers = User::where('visibility','1')->whereHas('roles',function($query){
            $query->where('roles','teacher');
        })->get();
        $groups = Group::all();
        $subjects = Subject::all();

        return view('admin.teacher_subject_group_assign.create', compact('teachers', 'groups', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherSubjectGroupAssignRequest $request)
    {
        $input = $request->validated();
        $input['days'] = implode(',',$input['days']);

        try{
            $groupSubject = GroupSubject::where('group_id',$input['group'])
                                            ->where('subject_id',$input['subject'])
                                            ->first();
                                            
            $groupSubject->users()->attach(
                $request->user,
                [
                    'days' => $input['days'],
                    'max_class_per_day' => $input['max_class_per_day']
                ]);
            return redirect()->route('teacher_subject_group_assign.list')->with('toast_success','Teacher Successfully Assosciated with Section Subject');

        }catch(Exception $e){
            Log::error('Error while assosciating teacher with subject-group '. $e);
            return back()->with('toast_error','Oops! Error Occured. The Teacher might have been already assigned to the same Subject and Section. Please confirm.');
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
    public function edit($groupSubjectId, $teacherId)
    {
        $groupSubject = GroupSubject::where('id', $groupSubjectId)
                        ->with([ 'users'=>function($query)use($teacherId){
                            $query->where('users.id',$teacherId);
                        }])
                        ->whereHas('users', function($query)use($teacherId){
                            $query->where('users.id',$teacherId);
                        })
                        ->first();

        $groupSubjectTeacher = GroupSubjectTeacher::where('group_subject_id',$groupSubject->id)
                                                    ->where('user_id',$teacherId)
                                                    ->first();

        // dd($groupSubject,$groupSubjectTeacher);
        $teachers = User::where('visibility','1')
                            ->whereHas('roles',function($query){
                                    $query->where('roles','teacher');
                                })
                            ->get();
        $groups = Group::all();
        $subjects = Subject::all();
        
        return view('admin.teacher_subject_group_assign.edit', compact('teachers', 'groups', 'subjects', 'groupSubject','groupSubjectTeacher'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherSubjectGroupAssignRequest $request, $groupSubjectTeacherId, $groupSubjectId)
    {
        $input = $request->validated();
        $input['days'] = implode(',',$input['days']);
        $previousGroupSubject = GroupSubject::where('id', $groupSubjectId)
                                    ->with(['users'=>function($query)use($request){
                                        $query->where('users.id', $request->prevTeacher);
                                    }])
                                    ->whereHas('users',function($query)use($request){
                                        $query->where('users.id', $request->prevTeacher);
                                    })
                                    ->first();
        try{
            $newGroupSubject = GroupSubject::where('group_id',$input['group'])
                                            ->where('subject_id',$input['subject'])
                                            ->first();

            $pivot = $previousGroupSubject->users->first()->pivot;
            $pivot->group_subject_id = $newGroupSubject->id;
            $pivot->user_id = $input['user'];
            $pivot->days = $input['days'];
            $pivot->max_class_per_day = $input['max_class_per_day'];
            $pivot->save();
            
            return redirect()->route('teacher_subject_group_assign.list')->with('toast_success','Teacher Assosciated with Section Subject Successfully Updated');
        }catch(Exception $e)
        {
            Log::error('Error while updating teacher with subject-group '. $e);
            return back()->with('toast_error','Oops! Error Occured. Please Try Again Later');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $groupSubject = GroupSubject::findOrFail($id);
        try{
            $groupSubject->users()->detach($request->teacher);
            return redirect()->route('teacher_subject_group_assign.list')->with('toast_success', 'Teacher Assosciated with Section Subject Has Been Deleted.');

        }catch(Exception $e){
            Log::error('Error while deleting assigned subject to group'.$e);
            return back()->with('toast_error', 'Oops! Error Occured. Please Try Again Later.');
        }
    }
}
