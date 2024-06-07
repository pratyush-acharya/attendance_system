<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'code',
        'type',
    ];

    /**
     * Defines many-to-many relationship between subject and group
     *
     * @return void
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)->withPivot('id')->withTrashed();
    }

    public function getTeachers()
    {
        $groupSubjectId = $this->pivot->id ?? $this->groups->first()->pivot->id;
        return User::whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
            $query->withTrashed()->where('group_subject_id', $groupSubjectId);
        })->get()->pluck('name')->toArray();
    }

    public function getTeacher()
    {
        $groupSubjectId = $this->pivot->id ?? $this->groups->first()->pivot->id;
        //dd($groupSubjectId);
        $user = User::whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
            $query->withTrashed()->where('group_subject_id', $groupSubjectId);
        })->first();
        //dd($user);
        return $user->name;
    }

    public function getClassDays()
    {
        $groupSubjectId = $this->pivot->id ?? $this->groups->first()->pivot->id;

        $user = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->withTrashed()->where('group_subject_id', $groupSubjectId);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->withTrashed()->where('group_subject_id', $groupSubjectId);
            })->first();

        return $user->groupSubjects->first()->pivot->days;
    }


    public function checkAttendance(Batch $batch)
    {
        $groupSubjectId = $this->pivot->id;

        $user = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->where('group_subject_id', $groupSubjectId);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->where('group_subject_id', $groupSubjectId);
            })->first();

        if ($user) {

            $groupSubjectTeacherId = $user->groupSubjects->first()->pivot->id;

            $presentCount = Attendance::whereHas('student', function ($query) use ($batch) {
                return $query->where('students.batch_id', $batch->id);
            })
                ->where('group_subject_teacher_id', $groupSubjectTeacherId)
                ->whereDate('date', date('Y-m-d'))
                ->get()
                ->count();
            return $presentCount;
        }

        return 0;
    }


    public function getPresentCount(Batch $batch)
    {
        return $this->getAttendanceCountBySubjectSection($batch, 'present');
    }

    public function getAbsentCount(Batch $batch)
    {
        return $this->getAttendanceCountBySubjectSection($batch, 'absent');
    }

    public function getLeaveCount(Batch $batch)
    {
        return $this->getAttendanceCountBySubjectSection($batch, 'leave');
    }

    private function getAttendanceCountBySubjectSection(Batch $batch, $status)
    {
        $groupSubjectId = $this->pivot->id ?? $this->groups->first()->pivot->id;

        $user = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->where('group_subject_id', $groupSubjectId);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->where('group_subject_id', $groupSubjectId);
            })->first();


        $groupSubjectTeacherId = $user->groupSubjects->first()->pivot->id;

        $count = Attendance::whereHas('student', function ($query) use ($batch) {
            return $query->where('students.batch_id', $batch->id);
        })->where('group_subject_teacher_id', $groupSubjectTeacherId)
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

    public function getAbsentees(Batch $batch)
    {
        $groupSubjectId = $this->pivot->id;

        $user = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->where('group_subject_id', $groupSubjectId);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->where('group_subject_id', $groupSubjectId);
            })->first();

        $groupSubjectTeacherId = $user->groupSubjects->first()->pivot->id;

        $presentCount = Attendance::whereHas('student', function ($query) use ($batch) {
            return $query->where('students.batch_id', $batch->id);
        })
            ->where('group_subject_teacher_id', $groupSubjectTeacherId)
            ->whereDate('created_at', date('Y-m-d'))
            ->where('absent', '!=', '0')
            ->get()
            ->map(function ($list) {
                return $list->student->name;
            });
        return $presentCount;
    }


    public function getOnLeaves(Batch $batch)
    {
        $groupSubjectId = $this->pivot->id;

        $user = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->where('group_subject_id', $groupSubjectId);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->where('group_subject_id', $groupSubjectId);
            })->first();

        $groupSubjectTeacherId = $user->groupSubjects->first()->pivot->id;

        $presentCount = Attendance::whereHas('student', function ($query) use ($batch) {
            return $query->where('students.batch_id', $batch->id);
        })
            ->where('group_subject_teacher_id', $groupSubjectTeacherId)
            ->whereDate('created_at', date('Y-m-d'))
            ->where('leave', '!=', '0')
            ->get()
            ->map(function ($list) {
                return $list->student->name;
            });
        return $presentCount;
    }

    public function getTotalPresentDays(Student $student, $startDate, $endDate)
    {
        return $this->getStudentAttendanceCount($student, $startDate, $endDate, $status = 'present');
    }

    public function getTotalAbsentDays(Student $student, $startDate, $endDate)
    {
        return $this->getStudentAttendanceCount($student, $startDate, $endDate, $status = 'absent');
    }

    public function getTotalLeaveDays(Student $student, $startDate, $endDate)
    {
        return $this->getStudentAttendanceCount($student, $startDate, $endDate, $status = 'leave');
    }

    public function getPresentPercentageByTeacher(Student $student, $startDate, $endDate)
    {
        $presentCounts = $this->getStudentAttendanceCountPerTeacher($student, $startDate, $endDate, $status = 'present');
        $totalClassCounts = $this->getTeachersTotalClasses($this->type == 'elective' ? $student->groups->where('type', 'optional')->first() ?? $student->groups->first() : $student->groups->first(), $startDate, $endDate);

        $teacherTotalClassCounts = [];
        for ($i = 0; $i < count($totalClassCounts); $i++) {
            $teacherTotalClassCounts[] = $totalClassCounts[$i] == '-' ? '-' : (round(((int)$presentCounts[$i] / $totalClassCounts[$i]) * 100, 2));
        }
        return $teacherTotalClassCounts;
    }

    public function getLeavePercentageByTeacher(Student $student, $startDate, $endDate)
    {
        $leaveCount = $this->getStudentAttendanceCountPerTeacher($student, $startDate, $endDate, $status = 'leave');
        $totalClassCounts = $this->getTeachersTotalClasses(($this->type == 'elective') ? $student->groups->where('type', 'optional')->first() ?? $student->groups->first() : $student->groups->first(), $startDate, $endDate);

        $teacherTotalClassCounts = [];
        for ($i = 0; $i < count($totalClassCounts); $i++) {
            $teacherTotalClassCounts[] = $totalClassCounts[$i] == '-' ? '-' : (round(((int)$leaveCount[$i] / $totalClassCounts[$i]) * 100, 2));
        }
        return $teacherTotalClassCounts;
    }

    public function getPresentPercentage(Student $student, $startDate, $endDate)
    {
        $presentCount = $this->getStudentAttendanceCount($student, $startDate, $endDate, $status = 'present');
        $totalClassCount = $this->getTotalClasses(($this->type == 'elective') ? $student->groups->where('type', 'optional')->first() ?? $student->groups->first() : $student->groups->first(), $startDate, $endDate);
        return $totalClassCount == '-' ? '-' : ($presentCount == '-' ? '-' : round(((int)$presentCount / $totalClassCount) * 100, 2));
    }

    public function getLeavePercentage(Student $student, $startDate, $endDate)
    {
        $leaveCount = $this->getStudentAttendanceCount($student, $startDate, $endDate, $status = 'leave');
        $totalClassCount = $this->getTotalClasses(($this->type == 'elective') ? $student->groups->where('type', 'optional')->first() ?? $student->groups->first() : $student->groups->first(), $startDate, $endDate);

        return $totalClassCount == '-' ? '-' : ($leaveCount == '-' ? 0 : round(((int)$leaveCount / $totalClassCount) * 100, 2));
    }

    private function getStudentAttendanceCount(Student $student, $startDate, $endDate, $status)
    {
        $groupSubjectId = GroupSubject::withTrashed()->where([
            ['subject_id', $this->id],
            ['group_id', ($this->type == 'elective') ? $student->groups->where('type', 'optional')->first()->id ?? $student->groups->first()->id : $student->groups->first()->id]
        ])->first();
        if (is_null($groupSubjectId)) {
            return '-';
        }

        $user = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->withTrashed()->where('group_subject_id', $groupSubjectId->id);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->withTrashed()->where('group_subject_id', $groupSubjectId->id);
            })->first();
        $groupSubjectTeacherId = $user->groupSubjects->first()->pivot->id;

        $attendance = Attendance::withTrashed()->where('student_id', $student->id)
            ->where('group_subject_teacher_id', $groupSubjectTeacherId)
            ->whereBetween('date', [$startDate ?? $student->batch->start_date, $endDate ?? date('Y-m-d')])
            ->get();

        return $attendance->isEmpty() ? '-' : $attendance->sum($status);
    }

    // My Additions ...
    public function getStudentAttendanceCountPerTeacher(Student $student, $startDate, $endDate, $status)
    {
        $groupSubjectId = GroupSubject::withTrashed()->where([
            ['subject_id', $this->id],
            ['group_id', ($this->type == 'elective') ? $student->groups->where('type', 'optional')->first()->id ?? $student->groups->first()->id : $student->groups->first()->id]
        ])->first();
        if (is_null($groupSubjectId)) {
            return '-';
        }

        $users = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->withTrashed()->where('group_subject_id', $groupSubjectId->id);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->withTrashed()->where('group_subject_id', $groupSubjectId->id);
            })->get();

        $groupSubjectTeacherIds = $users->map(function ($user, $key) {
            return $user->groupSubjects->first()->pivot->id;
        });

        return $groupSubjectTeacherIds->map(function ($groupSubjectTeacherId, $key) use ($student, $status) {
            $attendance = Attendance::withTrashed()->where('student_id', $student->id)
                ->where('group_subject_teacher_id', $groupSubjectTeacherId)
                ->whereBetween('date', [$startDate ?? $student->batch->start_date, $endDate ?? date('Y-m-d')])
                ->get();

            return $attendance->isEmpty() ? '-' : $attendance->sum($status);
        });
    }

    public function getTotalClasses(Group $group, $startDate, $endDate)
    {
        $groupSubjectId = GroupSubject::withTrashed()->where([
            ['subject_id', $this->id],
            ['group_id', $group->id]
        ])->first();

        if (is_null($groupSubjectId)) {
            return '-';
        }

        $user = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->withTrashed()->where('group_subject_id', $groupSubjectId->id);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->withTrashed()->where('group_subject_id', $groupSubjectId->id);
            })->first();

        $groupSubjectTeacherId = $user->groupSubjects->first()->pivot->id;

        $attendances = Attendance::withTrashed()->where('group_subject_teacher_id', $groupSubjectTeacherId)
            ->whereBetween('date', [$startDate ?? $this->groups->first()->batch->start_date, $endDate ?? date('Y-m-d')])
            ->get()
            ->groupBy('date');

        $totalClass = 0;

        foreach ($attendances as $attendance) {
            $attendance = $attendance->take(1);
            $totalClass = $totalClass + ($attendance->first()->present + $attendance->first()->absent + $attendance->first()->leave);
        }

        return $totalClass;
    }

    public function getTeachersTotalClasses(Group $group, $startDate, $endDate)
    {
        $groupSubjectId = GroupSubject::withTrashed()->where([
            ['subject_id', $this->id],
            ['group_id', $group->id]
        ])->first();

        if (is_null($groupSubjectId)) {
            return ['-'];
        }

        $users = User::with(['groupSubjects' => function ($query) use ($groupSubjectId) {
            $query->withTrashed()->where('group_subject_id', $groupSubjectId->id);
        }])
            ->whereHas('groupSubjects', function ($query) use ($groupSubjectId) {
                $query->withTrashed()->where('group_subject_id', $groupSubjectId->id);
            })->get();


        $groupSubjectTeacherIds = $users->map(function ($user, $key) {
            return $user->groupSubjects->first()->pivot->id;
        });
        
        $totalClassCounts = [];

        foreach($groupSubjectTeacherIds as $groupSubjectTeacherId){
            $classCount =DB::select(
                "SELECT sum(attendance_counts_by_date.count) as total_class_count FROM (SELECT date, CAST(sum(`present`+ `leave`+`absent`)/count(DISTINCT `student_id`)as DECIMAL(6,0)) as 'count'  FROM attendances WHERE group_subject_teacher_id = :groupSubjectTeacherId GROUP BY date) attendance_counts_by_date;",
                array('groupSubjectTeacherId' => $groupSubjectTeacherId)
            );

            array_push($totalClassCounts, $classCount[0]->total_class_count > 0 ? $classCount[0]->total_class_count : '-');
        }

        return $totalClassCounts;

    }
}
