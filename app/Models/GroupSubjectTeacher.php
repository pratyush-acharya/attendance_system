<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupSubjectTeacher extends Pivot
{
    use SoftDeletes;

    protected $table = 'group_subject_teacher';

    public $fillable = [
        'days',
        'max_class_per_day'
    ];

    public function groupSubject()
    {
        return $this->belongsTo(GroupSubject::class, 'group_subject_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function checkAttendance(Batch $batch)
    {
        $attendanceCount = Attendance::whereHas('student', function ($query) use ($batch) {
                                        return $query->where('students.batch_id', $batch->id);
                                    })
                                        ->where('group_subject_teacher_id', $this->id)
                                        ->whereDate('date', date('Y-m-d'))
                                        ->get()
                                        ->count();
        return $attendanceCount;
    }

    public function getPresentCount(Batch $batch)
    {
        return $this->getAttendanceCountByStatus($batch, 'present');
    }

    public function getAbsentCount(Batch $batch)
    {
        return $this->getAttendanceCountByStatus($batch, 'absent');
    }

    public function getLeaveCount(Batch $batch)
    {
        return $this->getAttendanceCountByStatus($batch, 'leave');
    }

    public function getAbsentees(Batch $batch)
    {
        $absentees  =  Attendance::whereHas('student',function($query)use($batch){
                            return $query->where('students.batch_id',$batch->id);
                        })
                        ->where('group_subject_teacher_id', $this->id)
                        ->whereDate('created_at', date('Y-m-d'))
                        ->where('absent','!=','0')  
                        ->get()
                        ->map(function($list){
                            return $list->student->name;
                        });

        return $absentees;
    }

    public function getOnLeaves(Batch $batch)
    {
        $presentCount = Attendance::whereHas('student',function($query)use($batch){
                            return $query->where('students.batch_id',$batch->id);
                        })
                        ->where('group_subject_teacher_id', $this->id)
                        ->whereDate('created_at', date('Y-m-d'))
                        ->where('leave','!=','0')
                        ->get()
                        ->map(function($list){
                            return $list->student->name;
                        });
        
        return $presentCount;
    }

    public function getAttendanceCountByStatus(Batch $batch, $status)
    {
        $count = Attendance::whereHas('student', function ($query) use ($batch) {
                                    return $query->where('students.batch_id', $batch->id);
                                })
                                ->where('group_subject_teacher_id', $this->id)
                                ->whereDate('created_at', date('Y-m-d'));

        if ($status == 'present') {
            $count = $count->where('absent', '0')
                            ->where('leave', '0')
                            ->get()
                            ->count();
        } elseif ($status == 'leave') {
            $count = $count->where('leave', '!=', '0')
                            ->get()
                            ->count();
        } elseif ($status == 'absent') {
            $count = $count->where('absent', '!=', '0')
                            ->get()
                            ->count();
        }
        return $count;
    }
}
