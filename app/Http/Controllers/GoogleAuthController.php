<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
      
            $user = Socialite::driver('google')->user();
            // first check if the user with google_id exists
            $finduser = Student::where('google_id', $user->id)->first();
            
            if($finduser){
       
                Auth::guard('student')->login($finduser);
      
                return redirect()->intended(route('student.dashboard'));
       
            }

            // now check if user with google email exists
            $checkUser = Student::where('email', $user->email)->first();

            if($checkUser){
                //update the user's google id
                $checkUser->google_id = $user->id;
                $checkUser->save();
                //now login the user

                Auth::guard('student')->login($checkUser);
                return redirect()->intended(route('student.dashboard'));
            }else{
                return redirect('/')->with('toast_error', 'You do not have the permission');
            }
      
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
