<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use App\Mail\UserCredentialMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // This is also USER CONTROLLER 


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select('id','name','email')->where('visibility','1')->with('roles:id,roles')->get();
        
        return view('admin.user.index')->with(compact('users'));
   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $input = $request->validated();

        $input['password']= bcrypt("Attendance@AMS");
        $roles = $input['role']; 
        $user_roles = [];
        $user = User::create($input);
        $user->roles()->sync($roles);     //insert in role_user pivot table
        foreach($roles as $role){
            array_push($user_roles,Role::select('roles')->where('id',$role)->first()->roles);
        }
        Mail::to($user->email)->send(new UserCredentialMail($user,$user_roles));

        return redirect()->route("user.list")->with('toast_success','User Created Successfully.');
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
        $user = User::with('roles:id,roles')->findOrFail($id);
        $roles = $user->roles;

        return view('admin.user.edit')->with(compact('user','roles'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
       $input = $request->validated();
       $role= $input['role'];

       $user =  User::findOrFail($id);

       $user->update($input);
       $user->roles()->sync($role);

       return redirect()->route("user.list")->with('toast_success','User Updated Successfully.');
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
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->roles()->detach();      // Detach all roles from the user...     
            $user->delete();                //then delete the user

            DB::commit();
            return redirect()->route("user.list")->with('toast_success','User Deleted Successfully.');
            
        }catch(\Illuminate\Database\QueryException $e){
            Log::error('Error while deleting User'.$e);
            if($e->getCode() == "23000"){
                return redirect()->back()->with('toast_error','User cannot be deleted.');
            }
            return redirect()->back()->with('toast_error','Oops! Error occured. Please try again later.');
        }
        
    }


     //Bulk Upload 
    public function createBulk(){       
        return view('admin.user.bulk');
    }

    public function storeBulk(UserRequest $request){
        $input = $request->validated();
        
        foreach($input['users'] as $user){
            $user_roles = [];

            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt("Attendance@AMS"),
            ]);

            $roles = $user['role'];
            $newUser->roles()->sync($roles);     //insert in role_user pivot table

            foreach($roles as $role){
                array_push($user_roles,Role::select('roles')->where('id',$role)->first()->roles);
            }
            Mail::to($newUser->email)->queue(new UserCredentialMail($newUser,$user_roles));
        }

        return redirect()->route('user.list')->with('toast_success', 'Users Created Successfully.');
    }
}
