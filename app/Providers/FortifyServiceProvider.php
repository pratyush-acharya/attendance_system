<?php

namespace App\Providers;

use App\Models\User;

use App\Http\Responses\LoginResponse;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        }); 
        
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);

        Fortify::loginView(function () {

            if(Auth::check()) {
                return redirect('/home');
            }elseif(Auth::guard('student')->check())
            {
                return redirect()->route('student.dashboard');
            }
            return view('auth.login');

            return view('auth.login');
        });

        Fortify::authenticateUsing(function (Request $request){
            $user = User::where('email', $request->email)->first();
            if ($user &&
            Hash::check($request->password, $user->password)) {
                if($user->hasRole($request->role)){
                    //Set session role login
                    Session::put('login_role', $request->role);
                    $user['last_login'] = date('Y-m-d H:i:s');
                    $user->update();
                    return $user;
                }else{
                    throw ValidationException::withMessages([
                        "role" => "Incorrect Email or Password.",
                    ]);
                }

                
            }
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.passwords.email');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.passwords.reset', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify'); 
        });
    }
}
