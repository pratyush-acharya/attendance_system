<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyPasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::guard('student')->check())
            if(Auth::user()->last_password_change_datetime == NULL || date('m',strtotime(Auth::user()->last_password_change_datetime)) == date('m')-2)
                return redirect()->route('change-password');
            else
                return $next($request);
        
        else
            return $next($request);
    }
}
