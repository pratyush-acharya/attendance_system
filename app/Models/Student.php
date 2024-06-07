<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Student extends Authenticatable
{
    use HasFactory, SoftDeletes;
    
    protected $guard = 'student';

    protected $hidden = ['id','google_id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[ 'roll_no', 'name', 'email','batch_id', 'semester', 'status' ];

    /**
     * Defines one-to-many-relationship between student and batch
     *
     * @return void
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class,'batch_id','id');
    }
    
        
    /**
     * Defines many-to-many relationship between groups and students
     *
     * @return void
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTrashed();
    }
    
    /**
     * Defines one-to-many relationship between student and attendance
     *
     * @return void
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
        
    public function getAbsentDays($id)
    {
        $attendances =  Attendance::where('student_id',$this->id)
                            ->where('attendances.group_subject_teacher_id',$id)
                            ->where('created_at','>', Carbon::now()->subDays(6))
                            ->sum('absent');
                           
        return $attendances;
    }

    public function getLeaveDays($id)
    {
        $attendances =  Attendance::where('student_id',$this->id)
                            ->where('attendances.group_subject_teacher_id',$id)
                            ->where('created_at','>', Carbon::now()->subDays(6))
                            ->sum('leave');
                           
        return $attendances;
    }

    public function getAttendanceByDays()
    {

        $temp=[];
        $attendance = $this->attendance->groupBy(function($query){
                                return Carbon::parse($query->date)->format('d');
                            })
                            ->map(function($attendance){
                                // dd($attendance->first()->present);
                                $temp['present'] = $attendance->first()->present;
                                $temp['absent'] = $attendance->first()->absent;
                                $temp['leave'] = $attendance->first()->leave;
                                return $temp;
                            });
        return $attendance;
    }

    public function hasBelowAttendanceFilter($subjects, $startDate, $endDate, $attendanceFilter)
    {
        foreach($subjects as $subject)
        {
            $presentPercentage = $subject->getPresentPercentage($this, $startDate ?? null, $endDate ?? null);

            if($presentPercentage == '-'){
                continue;
            }

            if($presentPercentage <= $attendanceFilter){
                return true;
                break;
            }
        }
        return false;
    }

    public function hasBelowAttendanceFilterOnAllSubjects($subjects, $startDate, $endDate, $attendanceFilter)
    {
        foreach($subjects as $subject)
        {
            $presentPercentage = $subject->getPresentPercentage($this, $startDate ?? null, $endDate ?? null);

            if($presentPercentage == '-'){
                continue;
            }

            if($presentPercentage >= $attendanceFilter){
                return false;
                break;
            }
        }
        return true;
    }
    

    
}
