<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BatchRequest extends FormRequest
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
            'name'=>'required|max:255|string|unique:batches,name,'.$this->id.',id,stream_id,'.$this->stream_id,
            'stream_id'=>['required','integer','exists:streams,id'],
            'start_date'=>'required|date',
            'end_date'=>'required|date|after:start_date',
            'semester'=>'required|integer|between:1,8',
        ];
    }
}
