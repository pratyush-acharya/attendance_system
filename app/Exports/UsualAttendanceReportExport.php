<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class UsualAttendanceReportExport implements FromView
{
    use Exportable;
    private $students;
    private $subjects;
    private $startDate;
    private $endDate;

    public function __construct(Collection $students, Collection $subjects, string $startDate = null, string $endDate = null)
    {
        $this->students = $students;
        $this->subjects = $subjects;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $students =  $this->students;
        $subjects = $this->subjects;
        $startDate = $this->startDate;
        $endDate = $this->endDate;

        return view('layouts.exports.usualReport', compact('students','subjects', 'startDate', 'endDate'));

    }
}
