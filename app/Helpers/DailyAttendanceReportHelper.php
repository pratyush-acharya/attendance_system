<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Htpp\Controllers\AttendanceController;
use Illuminate\Support\Facades\Mail;
use App\Models\Exam;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Group;
use App\Models\Holiday;
use App\Models\GroupSubjectTeacher;
use App\Models\GroupSubject;
use App\Helpers\MailHelper;
use App\Mail\DailyAttendanceReport;

class DailyAttendanceReportHelper{
    
    public static function dailyAttendanceReportMail()
    {
        $sent_mail = (new self)->sendDailyReportMail();

        return 1;
    }

    private function sendDailyReportMail(){
        $batches = Batch::all();
        $adminEmail = MailHelper::getAdminEmail();  

        foreach($batches as $batch){
            $batch_holiday = Holiday::where('date',date('Y-m-d'))
                            ->where('batch_id',$batch->id)
                            ->first();

            $students = Student::whereHas('attendance',function($query){    //Students whose attendances were taken today        
                                $query->where('date',date('Y-m-d'));
                            })
                            ->where('batch_id',$batch->id)
                            ->get();
            
        if($batch->start_date <= date('Y-m-d') && $batch->end_date >= date('Y-m-d')){
            // if holiday or not attendance should go 
            if(($batch_holiday == null && !$students->isEmpty()) || !$students->isEmpty()){
                $mainGroups = Group::where('batch_id',$batch->id)
                                ->where('type','compulsory')
                                ->get();

                $optionalGroups = Group::where('batch_id',$batch->id)
                                ->where('type','optional')
                                ->get(); 
                
                $mail = Mail::to($adminEmail)
                                ->cc([
                                    '_academics@deerwalk.edu.np',
                                    'aakancha.thapa@deerwalk.edu.np'
                                ])
                                ->bcc([
                                    'pratyush.acharya@deerwalk.edu.np',
                                    'nirnaya.dangol@deerwalk.edu.np',
                                    'nishant.khadka@deerwalk.edu.np'
                                ])
                                ->queue(new DailyAttendanceReport($batch,$mainGroups,$optionalGroups));

            }
        }
           
        }

        return true;
    }
}
?>