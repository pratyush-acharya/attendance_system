<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Exam;
use App\Http\Requests\ExamRequest;
class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::all();
        return view('admin.exam.index')->with(compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = ['mid','final','boards'];
        $batches = Batch::all();

        return view('admin.exam.create')->with(compact('types','batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request)
    {
        $input = $request->validated();
        try{
            $exam = Exam::create($input);
            return redirect()->route('exam.list')->with('toast_success','Exam Created Successfully.');
        }catch( Exception $e){
            Log::error("Error occurred while creating exam. Error report". $e);
            return redirect()->route('exam.list')->with('toast_error', 'Oops! Error Occured! Please Try Again Later!');
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
        $exam = Exam::findOrFail($id);
        $batches = Batch::all();
        $types = ['mid','final','boards'];

        return view('admin.exam.edit')->with(compact('exam','types','batches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExamRequest $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $input = $request->validated();
        $exam->update($input);
        return redirect()->route('exam.list')->with('toast_success','Exam Updated Successfully.');
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
            $exam = Exam::findOrFail($id);
            $exam->delete();
            return redirect()->route('exam.list')->with('toast_success','Exam Deleted Successfully.'); 
        }
        catch(\Illuminate\Database\QueryException $e){
            Log::error("Error while deleting group. Error report: ". $e);
            if($e->getCode() == "23000"){
                return redirect()->back()->with('toast_error','Exam Cannot be Deleted.');;
            }
            return redirect()->route('exam.list')->with('toast_error','Oops! Error Occured. Please Try Again');
            }
    }
}
