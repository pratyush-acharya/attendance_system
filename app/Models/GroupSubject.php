<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupSubject extends Pivot
{

    use SoftDeletes;
    
    protected $table = 'group_subject';

    protected $softDelete = true;

    /**
     * Defines many-to-many relationship between teacher and group-subject
     *
     * @return void
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_subject_teacher', 'group_subject_id', 'user_id')->withPivot('days', 'max_class_per_day');
    }


    /**
     * Get the group's name
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function groupId(): Attribute
    {
        return Attribute::make(
            get: function($value){
                $groupName = Group::where('id', $value)->first();
                return 'Section '.$groupName->name . ' of Class of ' . $groupName->batch->name . ' of stream ' . $groupName->batch->stream->name;
            },
        );
    }


    /**
     * Get the subject's name
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function subjectId(): Attribute
    {
        return Attribute::make(
            get: function($value){
                $subjectName = Subject::where('id', $value)->first();
                $subjectName = $subjectName->code . '-' . $subjectName->name; 
                return $subjectName;
            },
        );
    }


}
