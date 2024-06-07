<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentGroupAssignRequest extends FormRequest
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
        return [
            'stream_id' => 'required|exists:streams,id',
            'batch_id' => 'required|exists:batches,id',
            'students'=>'required',
            'students.*' => 'required|exists:students,id',
            'group_id'=>'required',
        ];
    }
}
