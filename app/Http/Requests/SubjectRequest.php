<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class SubjectRequest extends FormRequest
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
        if(Route::currentRouteName() != 'subject.store.bulk'){
            return [
                'name' => [ 'required', 'string', 'max:50' ],
                'code' => [ 'required', 'string', Rule::unique('subjects')->ignore($this->route()->id) ],
                'type' => [ 'required', Rule::in(['main','elective','credit'])],
            ];
        }else{
            return [
                'subjects.*.name' => ['required','string','max:50'],
                'subjects.*.code' => ['required', 'string', Rule::unique('subjects')->ignore($this->route()->id)],
                'subjects.*.type' => [ 'required', Rule::in(['main','elective','credit'])],
            ]; 
        }
       
    }
}
