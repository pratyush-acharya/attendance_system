<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Batch;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\CsvParser;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::withTrashed()->with('batch')->get();
        return view('admin.student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $batches = Batch::all();
        return view('admin.student.create', compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        $input = $request->validated();
        try{
            Student::create($input);
            return redirect()->route('student.list')->with('toast_success','Student Created Successfully.');
        }
        catch( Exception $e){
            Log::error('Error while creating student. Error report: '. $e);
            return redirect()->route('student.list')->with('toast_error', 'Oops! Error occured. Please try again later');
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
        $student = Student::findOrFail($id);
        $batches = Batch::all();

        return view('admin.student.edit', compact('student', 'batches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, $id)
    {
        $student = Student::findOrFail($id);
        $input = $request->validated();

        try{
            $student->update($input);
            return redirect()->route('student.list')->with('toast_success','Student Updated Successfully.');
        }
        catch(Exception $e){
            Log::error('Error while updating student. Error report: '. $e);
            return redirect()->route('student.list')->with('toast_error','Oops! Error occured. Please try again later.');
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
        $student = Student::findOrFail($id);

        try{
            $student->delete();
            return redirect()->route('student.list')->with('toast_success','Student Deleted Successfully.');
        }catch( Exception $e){
            Log::error('Error while deleting student. Error report: '. $e);
            if($e->getCode() == "23000"){
                return redirect()->back()->with('toast_error','Student cannot be deleted.');
            }
            return redirect()->route('student.list')->with('toast_error','Oops! Error occured. Please try again later.');
        }
        

    }
    /**
     * Store new students via csv
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'student_csv' => 'required|mimes:csv,txt'
        ]);

        $extension =$request->file('student_csv')->extension();
        $fileName=time().'.'.$extension;
        $path=$request->file('student_csv')->storeAs('public/csv',$fileName);

        $studentImport = new StudentsImport;

        try
        {
            $studentImport->import($path);
            Storage::delete($path);
            return redirect()->back()->with('toast_success',"Students Imported Successfully");
        }
        catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            $errorMessages = '';
            foreach($e->failures() as $failure)
            {
                $errorMessages .= str_replace('.', ' ',$failure->errors()[0]) . 'at row ' . $failure->row() . '.<br>';

            }
            Storage::delete($path);

            return redirect()->back()->with('errors', $errorMessages);
        }
         
    }

    public function getBulkUpload(){
        return view('admin.student.bulkUpload');
    }

    public function createBulk(){
        $batches = Batch::all();
        
        return view('admin.student.bulk')->with(compact('batches'));
    }

    public function storeBulk(StudentRequest $request){
        $input = $request->validated();
        $roll_no=[];
        try{
            foreach($input['students'] as $student){
                array_push($roll_no,$student['roll_no']);
            }
            if(count($input['students'])>count(array_unique($roll_no))){
                return redirect()->back()->with('toast_error','Duplicate Roll Numbers found. Please use distinct Roll Numbers.');
            }else{
                foreach($input['students'] as $student){
                    $student = Student::create([
                        'batch_id' => $input['batch_id'],
                        'name' => $student['name'],
                        'email' => $student['email'],
                        'roll_no' => $student['roll_no']
                    ]);
                }
            }
        return redirect()->route('student.list')->with('toast_success', 'Students Created Successfully.');

        }catch(e){
            Log::error('Error while creating student. Error report: '. $e);
            return redirect()->route('student.list')->with('toast_error','Oops! Error occured. Please try again later.');
        }
        
    }
    
    public function restore($id)
    {
        try{
            Student::withTrashed()->where('id', $id)->restore();
            return redirect()->route('student.list')->with('toast_success','Student Restored Successfully.');
        }catch( Exception $e){
            Log::error('Error while restoring student. Error report: '. $e);
            return redirect()->route('student.list')->with('toast_error','Oops! Error occured. Please try again later.');
        }
    }
}

