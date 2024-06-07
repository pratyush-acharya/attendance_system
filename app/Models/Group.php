<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable=[
        'name',
        'type',
        'batch_id'
    ];


     /**
     * Defines many-to-many relationship between  group and subject
     *
     * @return void
     */
    public function subjects(){
        return $this->belongsToMany(Subject::class)->withPivot('id');
    }
    
    /**
     * Defines one-to-many relationship between batch and group
     *
     * @return void
     */
    public function batch(){
        return $this->belongsTo(Batch::class);
    }

        
    /**
     * Defines many-to-many relationship between students and group
     *
     * @return void
     */
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
    

    public function filterSubject()
    {
        $subjects = $this->subjects;
        $requiredGroupSubjectTeachers = [];
        foreach($subjects as $subject)
        {
            $groupSubjectId = $subject->pivot->id;

            $groupSubjectTeachers =  GroupSubjectTeacher::where('group_subject_id',$groupSubjectId)->get();

            foreach($groupSubjectTeachers as $groupSubjectTeacher)
            {
                $is_today_class = in_array(date('D'),explode(',',$groupSubjectTeacher->days));

                if($is_today_class)
                {
                    array_push($requiredGroupSubjectTeachers, $groupSubjectTeacher);
                }
                else{
                    $attendances = Attendance::where('group_subject_teacher_id',$groupSubjectTeacher->id)
                                ->where('date',date('Y-m-d'))
                                ->get();
                    if($attendances->count() > 0)
                    {
                        array_push($requiredGroupSubjectTeachers, $groupSubjectTeacher);
                    }

                }
            }
        }
        return $requiredGroupSubjectTeachers;
    }
}
