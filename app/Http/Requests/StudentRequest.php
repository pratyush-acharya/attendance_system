<?php

namespace App\Http\Requests;

use App\Rules\StudentCsvBatch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    /**
     * Indicates whether validation should stop after the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;
    
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
        // 'student.update'
        if(Route::currentRouteName() == 'student.update'){
            return [
                'batch_id'  => ['required', 'exists:batches,id'],
                'email' => ['required', 'string', 'email:rfc,dns', 'max:255',
                                Rule::unique('students')->ignore($this->route()->id),
                            ],
                'roll_no' => ['required',Rule::unique('students')->ignore($this->route()->id)],
                'name' => ['required','string','max:100'],
                'students.*.name' => ['required','string','max:100'],
                'students.*.email' => ['required', Rule::unique('students')->ignore($this->route()->id)],
                'students.*.roll_no' => ['required',Rule::unique('students')->ignore($this->route()->id)]
                        ];
        }

        return [
            'batch_id'  => ['required', 'exists:batches,id'],
            'students.*.name' => ['required','string','max:100'],
            'students.*.email' => ['required', Rule::unique('students')->ignore($this->route()->id)],
            'students.*.roll_no' => ['required',Rule::unique('students')->ignore($this->route()->id)]
        ];   
    }
}
