<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\FeedbackEmail;
use App\Models\Feedback;
use App\Models\User;
use App\Helpers\MailHelper;
use App\Mail\FeedbackMail;  

class TeacherFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = Feedback::where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->get();
        return view('teacher.feedback.index')->with(compact('feedbacks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::select('id','email')->where('visibility','1')->get();
        return view('teacher.feedback.create')->with(compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedbackRequest $request)
    {
        $input = $request->validated();
        $input['user_id'] = Auth::user()->id;
        
        $email_to = FeedbackEmail::first()->email_to;
        $email_cc = FeedbackEmail::first()->email_cc;

        $to = explode(",",$email_to);
        $cc = explode(",",$email_cc);

        if(array_key_exists('image',$input) && $input['image'] != null){
            $input['image'] = $input['image']->store('feedback');            
        }
        try{
            $feedback = Feedback::create($input);
            if($feedback){
                if(empty($cc[0]))
                    Mail::to($to)->queue(new FeedbackMail($feedback));
                else
                    Mail::to($to)->cc($cc)->queue(new FeedbackMail($feedback));

            }
       
            return redirect()->route('teacher.feedback.list')->with('toast_success','Feedback Submitted Successfully');
        }catch(Exception $e){
            // dd($e);
            Log::error("Error while submitting feedback.". $e);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FeedbackRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $path = $feedback['image'];
        $user_id = $feedback['user_id'];
        $status = $feedback['status'];
        try{          
            if(strtolower($status) == 'pending' && (Session('role') == 'teacher') && (Auth::user()->id == $user_id)){
                 if($path!=null && Storage::exists($path)){
                    Storage::delete($path);
                }
                $feedback->delete();
                return redirect()->route('teacher.feedback.list')->with('toast_success','Feedback Deleted Successfully');
            }else{
                return back()->with('toast_error','Oops! Error Occured. Please Try Again Later');
            }
        }catch( Exception $e){
                Log::error("Error while deleting Feedback. Error report: ". $e);
                return redirect()->route('feedback.list')->with('toast_error','Oops! Error Occured. Please Try Again');
        }
        
    }

    
    public function download($id){
        $feedback = feedback::findOrFail($id);
        $path = $feedback['image'];
        $user_id = $feedback['user_id'];
        return Storage::download($path);
    }

    public function accept($id)
    {
        $feedback = feedback::findOrFail($id);
        $feedback->update([
            'status' => 'accepted',
        ]);
        return redirect()->back()->with('toast_success','Feedback Accepted Successfully');
    }

    public function reject($id)
    {
        feedback::findOrFail($id)
        ->update([
            'status' => 'rejected',
        ]);
        return redirect()->back()->with('toast_success','Feedback Rejected Successfully');
    }

    public function reissue(Request $request)
    {
        $feedback = Feedback::findOrFail($request->id);

        $feedback->update([
            'status' => 'pending',
            'reissue_date' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('toast_success','Feedback Reissued Successfully');
    }
}
