<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$role)
    {
        //first we check if the user is authenticated or not
        //if not authenticated then we redirect to the
        //login route

        if(!Auth::guard('student')->check() && !Auth::check()){
            return redirect()->route('login');
        }
        //put the role in the session variable for displaying
        //contents based on this role
        Session::put('role',$role);

        if(auth()->guard('student')->check())
        {
            return redirect()->route('student.dashboard');
        }

        if(!is_null(auth()->user()))
        {
            if(session('login_role') != $role)
            {
                return redirect(session('login_role') == "admin" ? "/home": "/dashboard");
            }

            $home = $role == "admin"? "/dashboard":"/home";

            if (! $request->user()->hasRole($role)) {
                return redirect($home);
            }
        }

        return $next($request);
    }
}
