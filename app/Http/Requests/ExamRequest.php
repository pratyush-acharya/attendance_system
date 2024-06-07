<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'batch_id'=>'required|integer|exists:batches,id',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after:start_date',
            'type'=>'required|string|in:mid,final,boards',
        ];
    }
}
