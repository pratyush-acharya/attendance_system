<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
        return [
            'teacherSubjectGroup'                       => [ 'required', 'exists:group_subject_teacher,id' ],
            'attendances'                               => [ 'required' ],
            'attendances.*'                             => [ 'required' ],
            'attendances.*.rollNo'                      => [ 'required', 'exists:students,roll_no' ],
            'attendances.*.attendanceStatus'            => [ 'required' ],
            'attendances.*.attendanceStatus.present'    => [ 'required', 'numeric' ],
            'attendances.*.attendanceStatus.absent'     => [ 'required', 'numeric' ],
            'attendances.*.attendanceStatus.leave'      => [ 'required', 'numeric' ],

        ];
    }
}
