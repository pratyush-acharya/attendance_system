<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Stream;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::all();
        return view('admin.subject.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $streams = Stream::all();

        return view('admin.subject.create', compact('streams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectRequest $request)
    {
        $input = $request->validated();

        try{
            $subject = Subject::create($input);
            return redirect()->route('subject.list')->with('toast_success', 'Subject Created Successfully.');

        }catch( Exception $e){
            Log::error("Error while uploading subject. Error report: ". $e);
            return redirect()->route('subject.list')->with('toast_error', 'Oops! Error Occured. Please Try Again!');

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
        $subject = Subject::findOrFail($id);

        $streams = Stream::all();

        return view('admin.subject.edit',compact('subject','streams'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectRequest $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $input = $request->validated();

        try{

            $subject->update($input);
            return redirect()->route('subject.list')->with('toast_success', 'Subject Updated Successfully.');

        }
        catch(Exception $e){
            Log::error("Error Occured while updating subject. Error Report: ".$e);
            return redirect()->route('subject.list')->with('toast_error', 'Oops! Error Occured. Please Try Again!');
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
        $subject = Subject::findOrFail($id);

        try{
            $subject->groups()->detach();
            $subject->delete();
            return redirect()->route('subject.list')->with('toast_success','Subject Deleted Successfully');
        }catch( Exception $e){
            Log::error('Error while deleting subject. Error report: '. $e);
            if($e->getCode() == "23000"){
                return redirect()->back()->with('toast_error','Subject cannot be deleted.');
            }
            return redirect()->route('subject.list')->with('toast_error','Oops! Error occured. Please try again later.');
        }
    }

    public function search()
    {
        $subjects = Subject::whereHas('groups', function(Builder $query){
                        $query->where('groups.id',request()->input('group'));
                    })->pluck('name','id');

        return response()->json($subjects);
    }



    //Bulk Upload 
    public function createBulk(){       
        return view('admin.subject.bulk');
    }

    public function storeBulk(SubjectRequest $request){
        $input = $request->validated();
        $subject_code = [];
        // dd($input);
        try{
            foreach($input['subjects'] as $subject){
                array_push($subject_code,$subject['code']);
            }
            if(count($input['subjects'])>count(array_unique($subject_code))){
                return redirect()->back()->with('toast_error','Duplicate Subject Code found. Please use distinct Subject Code.');
            }
            foreach($input['subjects'] as $subject){
                $subject = Subject::create([
                    'name' => $subject['name'],
                    'code' => $subject['code'],
                    'type' => $subject['type'],
                ]);
            }
        }catch(Exception $e){
            Log::error("Error while uploading subject. Error report: ". $e);
            return redirect()->route('subject.list')->with('toast_error', 'Oops! Error Occured. Please Try Again!');
        }

        return redirect()->route('subject.list')->with('toast_success', 'Subjects Created Successfully.');
    }
}
