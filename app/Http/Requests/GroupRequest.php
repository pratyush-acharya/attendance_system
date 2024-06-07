<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
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
            'name'=>'required|max:255|string|unique:groups,name,'.$this->id.',id,batch_id,'.$this->batch_id,
            'type'      => ['required', 'string', Rule::in(['optional', 'compulsory'])],
            'batch_id'  => ['required', 'exists:batches,id']
        ];
    }
}
