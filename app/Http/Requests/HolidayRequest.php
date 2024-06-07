<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class HolidayRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $dates = explode(", ", $this->date);
        $parsedDate = [];
        foreach($dates  as $date)
        {
            $dateParse = Carbon::parse($date)->format('Y-m-d');

            array_push($parsedDate, $dateParse);
        }

        $this->merge([
            'date' => $parsedDate,
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required|string|max:255',
            'date'=>'required',
            'date.*'=>'required|date',
            'batch_id'=>'exists:batches,id|nullable',
        ];
    }
}
