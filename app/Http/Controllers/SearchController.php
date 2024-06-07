<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Group;
class SearchController extends Controller
{
    //Batch LiveSearch
    public function searchBatch(Request $request){
        $batches = [];

        if($request->has('stream') && $request->stream != NULL){
            $stream_id=(int)$request->stream;
            if($request->has('batch'))
            {
                $keyword = $request->batch;
                $batches = Batch::select('id','name','stream_id')
                            ->where('stream_id',$stream_id)
                            ->where('name','like','%'.$keyword.'%')
                            ->get();
            }else{
                $batches = Batch::select('id','name','stream_id')
                            ->where('stream_id',$stream_id)
                            ->get();
            }
        }      

        return response()->json($batches);
    }

    //Student Livesearch
    public function searchStudent(Request $request){   
        $students = [];
       
        if($request->has('stream') && $request->stream != NULL && $request->has('batch') && $request->batch != NULL){
            $stream_id=(int)$request->stream;
            $batch_id=(int)$request->batch;
        
            if($request->has('student'))
            {
                $keyword = $request->student;
                $students = Student::select('id','name','batch_id')
                            ->with('batch',function($query) use($stream_id){
                                $query->where('stream_id',$stream_id);
                            })
                            ->where('batch_id',$batch_id)
                            ->where('name','like','%'.$keyword.'%')
                            ->get();
            }else{
                $students = Student::select('id','name','batch_id')
                             ->with('batch',function($query) use($stream_id){
                                $query->where('stream_id',$stream_id);
                            })
                            ->where('batch_id',$batch_id)
                            ->get();
            }
        }      
        return response()->json($students);
    }

    public function searchGroup(Request $request){   
        $groups = [];
       
        if($request->has('stream') && $request->stream != NULL && $request->has('batch') && $request->batch != NULL){
            $stream_id=(int)$request->stream;
            $batch_id=(int)$request->batch;
        
            if($request->has('group'))
            {
                $keyword = $request->group;
                $groups = Group::select('id','name','batch_id')
                            ->with('batch',function($query) use($stream_id){
                                $query->where('stream_id',$stream_id);
                            })
                            ->where('batch_id',$batch_id)
                            ->where('name','like','%'.$keyword.'%')
                            ->get();
            }else{
                $groups = Group::select('id','name','batch_id')
                             ->with('batch',function($query) use($stream_id){
                                $query->where('stream_id',$stream_id);
                            })
                            ->where('batch_id',$batch_id)
                            ->get();
            }
        }      
        return response()->json($groups);
    }
}
