<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FeedbackEmailRequest;
use App\Models\FeedbackEmail;
use App\Models\User;

class FeedbackEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbackEmails = FeedbackEmail::get();
        $count = count($feedbackEmails);
        return view('admin.feedbackEmail.index')->with(compact('feedbackEmails','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $feedbackEmail = [];
        $users = User::select('id','email')->where('visibility','1')->get();
        return view('admin.feedbackEmail.create')->with(compact('users','feedbackEmail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedbackEmailRequest $request)
    {
        try{
            $input = $request->validated();
            $input['email_to'] = implode(',',$input['email_tos']);
            
            if(array_key_exists("email_ccs",$input)){
                $input['email_cc'] = implode(',',$input['email_ccs']);
                $cc = explode(",",$input['email_cc']);
                unset($input['email_ccs']);
            }

            unset($input['email_tos']);
            FeedbackEmail::create($input);
            return redirect()->route('feedbackEmail.list')->with('toast_success','Feedback Email created successfully');
        }catch(Exception $e){
            Log::error("Error while submitting Feedback Email.". $e);
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
        $feedbackEmail = FeedbackEmail::find($id);
        $users = User::select('id','email')->where('visibility','1')->get();
        return view('admin.feedbackEmail.edit')->with(compact('feedbackEmail','users'));       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FeedbackEmailRequest $request, $id)
    {
        try{
            $feedbackEmail = FeedbackEmail::findOrFail($id);
            $input = $request->validated();

            $input['email_to'] = implode(',',$input['email_tos']);
            
            if(array_key_exists("email_ccs",$input)){
                $input['email_cc'] = implode(',',$input['email_ccs']);
                $cc = explode(",",$input['email_cc']);
                unset($input['email_ccs']);
            }
            
            unset($input['email_tos']);
            $feedbackEmail->update($input);

            return redirect()->route('feedbackEmail.list')->with('toast_success','Feedback Email Updated Successfully.');
        }catch(Exception $e){
            Log::error("Error while Updating Feedback Email.". $e);
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
        try{
            $feedbackEmail = feedbackEmail::findOrFail($id);
            $feedbackEmail->delete();
            return redirect()->route('feedbackEmail.list')->with('toast_success','Feedback Email Deleted Successfully.'); 
        }
        catch(\Illuminate\Database\QueryException $e){
            Log::error("Error while deleting Feedback Email. Error report: ". $e);
            if($e->getCode() == "23000"){
                return redirect()->back()->with('toast_error','Feedback Email Cannot be Deleted.');;
            }
            return redirect()->route('feedbackEmail.list')->with('toast_error','Oops! Error Occured. Please Try Again');
        }
    }
}
