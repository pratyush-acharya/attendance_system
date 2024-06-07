<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\GroupSubject;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SoftDeleteAtBatchEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft Deletes relevant models at the end of batch end date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $batches = Batch::all();

        foreach($batches as $batch)
        {
            if($batch->end_date <= date('Y-m-d'))
            {
                foreach($batch->groups as $group)
                {
                    //soft delete all group student 
                    DB::table('group_student')->where('group_id',$group->id)->update(array('deleted_at'=>  date('Y-m-d')));
                    
                    //soft delete all group_subject
                    $groupSubjects = GroupSubject::where('group_id', $group->id)->get();

                    foreach($groupSubjects as $groupSubject)
                    {
                        //soft delete all group subject teacher assosciated with group subject
                        $groupSubjectTeachers = DB::table('group_subject_teacher')->where('group_subject_id', $groupSubject->id)->get();

                        foreach($groupSubjectTeachers as $groupSubjectTeacher)
                        {
                            //soft delete all attendance assosciated with group subject teacher
                            Attendance::where('group_subject_teacher_id', $groupSubjectTeacher->id)->delete();
                            // $groupSubjectTeacher->update(array('deleted_at'=>  date('Y-m-d')));
                        }
                        DB::table('group_subject_teacher')->where('group_subject_id', $groupSubject->id)->update(array('deleted_at'=>  date('Y-m-d')));
                        // $groupSubject->update(array('deleted_at'=>  date('Y-m-d')));
                    }
                    GroupSubject::where('group_id', $group->id)->update(array('deleted_at'=>  date('Y-m-d')));
                    
                    //soft delete group
                    $group->delete();

                }
            }
        }
    }
}
