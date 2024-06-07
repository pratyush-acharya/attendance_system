<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use App\Models\GroupSubject;

class TeacherSubjectGroupAssignRequest extends FormRequest
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
        // dd($this->groupSubjectId,$this->groupSubjectTeacherId);
        return [
            'group'     => [ 'required', 'exists:groups,id' ],
            'subject'   => [ 'required', 'exists:subjects,id'],
            'user'      =>  ['required','exists:users,id', 
                                Rule::unique('group_subject_teacher','user_id')->where(fn($query)=>
                                    $query->where('group_subject_id', $this->groupSubjectId)
                                )
                                ->ignore($this->groupSubjectTeacherId)
                            ],
            // 'user'      => 'required|exists:users,id| unique:group_subject_teacher,user_id,'.$this->groupSubjectTeacherId.',id,group_subject_id,'.$this->groupSubjectId,
            'days'      => [ 'required', 'array'],
            'max_class_per_day' => [ 'required', 'integer', 'min:1' ],
        ];
    }

    public function messages(){
        return[
            'user.unique' => 'The user has already been assigned to same subject and section.'
        ];
    }
}
