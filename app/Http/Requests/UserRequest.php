<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(strtolower(session('role')) == "admin")
            return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(Route::currentRouteName() != 'user.store.bulk'){
            return [
                'name'=>['required','string','max:255'],
                'email' => ['required', 'string', 'email:rfc,dns', 'max:255',
                                Rule::unique('users')->ignore($this->route()->id),
                            ],
                'role.*'=>['required','exists:roles,id'],        
                'role'=>['required'],  
            ];
        }else{
            return [
                'users.*.name' => ['required','string','max:255'],
                'users.*.email' => ['required', 'string', 'email:rfc,dns', 'max:255',
                                Rule::unique('users')->ignore($this->route()->id),
                            ],
                'users.*.role.*' => ['required','exists:roles,id'],  
                'users.*.role'=>['required'],  

            ]; 
        }
        
    }
}
