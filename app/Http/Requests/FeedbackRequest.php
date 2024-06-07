<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(strtolower(session('role')) == "admin" || strtolower(session('role')) == "teacher")
            return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd(\Request::all());
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1024',
            'image' => 'file|mimes:jpg,png,jpeg',
            // 'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf',
            'status' => 'enum:pending,approved,rejected,reissue|default:pending',
        ];
    }
}
