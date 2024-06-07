<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Htpp\Controllers\AttendanceController;
use Illuminate\Support\Facades\Mail;
use App\Mail\Test;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Group;
use App\Helpers\MailHelper;
use App\Helpers\DailyAttendanceReportHelper;
use App\Mail\DailyAttendanceReport;



class DailyAttendanceMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Attendance Mail';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dailyAttendanceReportHelper = new DailyAttendanceReportHelper();
        $dailyAttendanceReportHelper->dailyAttendanceReportMail();

        return 1;
    }
}
