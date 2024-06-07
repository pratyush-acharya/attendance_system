<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class MailHelper{

    public static function getAdminEmail()
    {
        $admin = User::select('email')->where('visibility','1')->whereHas('roles',function($q){
            $q->where('roles','admin');
        })
        ->get()
        ->map(function($user){
            return $user->email;
        })->toArray();

        return $admin;
    }

}
?>