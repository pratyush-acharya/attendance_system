<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stream;
use App\Http\Requests\StreamRequest;
use Illuminate\Support\Facades\Log;

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $streams = Stream::select('name','id')->get();
        return view('admin.stream.index')->with(compact('streams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.stream.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StreamRequest $request)
    {
        $input = $request->validated();
        Stream::create($input);
        return redirect()->route('stream.list')->with('toast_success','Stream Created Successfully.');;
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
        $stream = Stream::findOrFail($id);
        return view('admin.stream.edit')->with(compact('stream'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StreamRequest $request, $id)
    {
        $input = $request->validated();
        $stream = Stream::findOrFail($id);
        $stream->update($input);
        return redirect()->route('stream.list')->with('toast_success','Stream Updated Successfully.');

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
            $stream = Stream::findOrFail($id);
            $stream->delete();
            return redirect()->route("stream.list")->with('toast_success','Stream Deleted Successfully.');
        }catch(\Illuminate\Database\QueryException $e){
            Log::error('Error while deleting student. Error report: '. $e);
            if($e->getCode() == "23000"){
                return redirect()->back()->with('toast_error','Stream Cannot be Deleted.');;
            }
            return redirect()->back()->with('toast_error','Oops! Error occured. Please try again later.');

        }
    }
}
