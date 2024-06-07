<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\IcsEventRequest;
use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;
use App\Models\Batch;
use App\Helpers\IcsEventHelper;
use Exception;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holidays = Holiday::all();
        return view('admin.holiday.index')->with(compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $batches = Batch::all();
        return view('admin.holiday.create')->with(compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HolidayRequest $request)
    {

        $input = $request->validated();
        try{
            if($input['batch_id'] == null){
                foreach($input['date'] as $date){
                    $batches = Batch::all();
                    foreach($batches as $batch){
                        $holiday = new Holiday();
                        $holiday->name = $input['name'];
                        $holiday->date = $date;
                        $holiday->batch_id = $batch->id;
                        $holiday->save();
                    }
                }
            }else{
                foreach($input['date'] as $date)
                {
                    $holiday = new Holiday();
                    $holiday->name = $input['name'];
                    $holiday->date = $date;
                    $holiday->batch_id = $input['batch_id'];
                    $holiday->save();
                }
            }
                
            // $holiday = Holiday::create($input);
            return redirect()->route('holiday.list')->with('toast_success','Holiday Created Successfully.');
        }catch( Exception $e){
            Log::error("Error occurred while creating Holiday. Error report". $e);
            return redirect()->route('holiday.list')->with('toast_error', 'Oops! Error Occured! Please Try Again Later!');
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
        $holiday = Holiday::findOrFail($id);
        $batches = Batch::all();

        return view('admin.holiday.edit')->with(compact('holiday','batches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HolidayRequest $request, $id)
    {
        $holiday = Holiday::findOrFail($id);
        $input = $request->validated();
        if(count($input['date'])>1)
        {
            return back()->with('toast_error', 'Please select only one day to edit');
        }

        foreach($input['date'] as $date)
        {
            $holiday->name = $input['name'];
            $holiday->date = $date;
            $holiday->batch_id = $input['batch_id'];
            $holiday->save();
        }
        return redirect()->route('holiday.list')->with('toast_success','Holiday Updated Successfully.');
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
            $holiday = Holiday::findOrFail($id);
            $holiday->delete();
            return redirect()->route('holiday.list')->with('toast_success','Holiday Deleted Successfully.'); 
        }
        catch(\Illuminate\Database\QueryException $e){
            Log::error("Error while deleting group. Error report: ". $e);
            if($e->getCode() == "23000"){
                return redirect()->back()->with('toast_error','Holiday Cannot be Deleted.');;
            }
            return redirect()->route('holiday.list')->with('toast_error','Oops! Error Occured. Please Try Again');
        }
    }


    public function storeIcsEvents(IcsEventRequest $request){
        $input = $request->validated();

        try{

            $ics_obj = new IcsEventHelper();
            $icsEvents = $ics_obj->getIcsEventsAsArray( $input['file'] );
        
            foreach( $icsEvents as $icsEvent){
                $start = isset( $icsEvent ['DTSTART;VALUE=DATE'] ) ? $icsEvent ['DTSTART;VALUE=DATE'] : $icsEvent ['DTSTART'];
                $startDate = date( 'Y-m-d',strtotime($start) );
                
                /* Getting end date with time */
                $end = isset( $icsEvent ['DTEND;VALUE=DATE'] ) ? $icsEvent ['DTEND;VALUE=DATE'] : $icsEvent ['DTEND'];
                $endDate = date( 'Y-m-d',strtotime($end) );
                
                $summary = $icsEvent['SUMMARY'];
                // dd($summary);
                $summary_array = explode("||",$summary);

                if($summary_array == false)
                    $summary_array = [$summary];

                $summary_array = array_map(function($item) {
                    return trim($item);
                },$summary_array);

                foreach($summary_array as $summary_detail){
                    $eventName = $summary_detail;
                    $details = explode(" ",$summary_detail);

                    $batch_id = Batch::select('id')->whereHas('stream', function($q) use ($details) {
                                            $q->where('name', $details[3]);
                                        })
                                        ->where('name', $details[4])
                                        ->first()
                                        ->id;
                    Holiday::create([
                        'name' => $eventName,
                        'date' => $startDate,
                        'batch_id' => $batch_id
                    ]);
                }
            }
            return redirect()->route('holiday.list')->with('toast_success','ICS Events Added Successfully.');
        }
        catch(\Exception $e){
            Log::error("Error while adding ics events. Error report: ". $e);
            return redirect()->route('holiday.list')->with('toast_error', 'Oops! Error Occured! Please Try Again Later!');
        }
        
    }

    public function createIcsEvents(){
        return view('admin.holiday.createIcs');
    }
}
