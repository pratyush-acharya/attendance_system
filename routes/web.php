<?php
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FeedbackEmailController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectGroupAssignController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AttendancePermissionController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentGroupAssignController;
use App\Http\Controllers\StudentViewController;
use App\Http\Controllers\Teacher\DashboardController as ControllersTeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherFeedbackController;
use App\Http\Controllers\TeacherSubjectGroupAssignController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()) {
        return redirect('/home');
    }elseif(Auth::guard('student')->check())
    {
        return redirect()->route('student.dashboard');
    }
    return view('auth.login');
});

Route::controller(GoogleAuthController::class)->group( function(){
    Route::get('/auth/google', 'redirectToGoogle')->name('redirectToGoogle');
Route::get('/auth/google/callback', 'handleGoogleCallback');
});

//change-password
Route::view('change-password','auth.passwords.changePassword')->name('change-password');

Route::group(['middleware'=>['role:teacher','password.change']],function(){
    //change-password
    // Route::view('change-password','auth.passwords.changePassword');
    Route::get('/dashboard',[ControllersTeacherDashboardController::class, 'index'])->name('teacher.dashboard.index');

    Route::controller(TeacherFeedbackController::class)->group( function(){
        Route::get('/teacher/feedback/create','create')->name('teacher.feedback.create');
        Route::post('/teacher/feedback','store')->name('teacher.feedback.store');
        Route::get('/teacher/feedback','index')->name('teacher.feedback.list');
        Route::get('/teacher/feedback/{id}/edit','edit')->name('teacher.feedback.edit');
        Route::put('/teacher/feedback/{id}', 'update')->name('teacher.feedback.update');
        Route::delete('/teacher/feedback/{id}','destroy')->name('teacher.feedback.delete');
        Route::get('/teacher/feedback/download/{id}','download')->name('teacher.feedback.download');
        Route::put('/teacher/feedback/reissue/{id}','reissue')->name('teacher.feedback.reissue');

    });

    Route::controller(AttendanceController::class)->group( function(){
        Route::get('/attendance/{teacher_group_subject_id}','index')->name('attendance.create');
        Route::post('/attendance','store')->name('attendance.store');
    });

    Route::controller(ReportController::class)->group( function(){

        Route::get('/teacher-report','teacherView')->name('report.teacherView');
        Route::get('/teacher-report/search', 'teacherSearch')->name('report.teacherSearch');
        Route::get('/teacher-report/batchSearch', 'teacherBatchSearch')->name('report.teacherBatchSearch');
        Route::post('/download/teacher-report', 'teacherDownload')->name('report.teacherDownload');

    });


});


// Admin
Route::group(['middleware'=>['role:admin','password.change']],function(){

    // Route::view('home','home');
    Route::controller(HolidayController::class)->group( function(){
        Route::get('/holiday/create','create')->name("holiday.create");
        Route::post('/holiday','store')->name("holiday.store");
        Route::get('/holiday','index')->name("holiday.list");
        Route::get('/holiday/{id}/edit', 'edit')->name("holiday.edit");
        Route::put('/holiday/{id}', 'update')->name("holiday.update");
        Route::delete('/holiday/{id}', 'destroy')->name("holiday.delete");

        Route::post('/holiday/ics-event','storeIcsEvents')->name("ics.store");
        Route::get('/holiday/ics-event','createIcsEvents')->name("ics.create");

    });

    Route::controller(ExamController::class)->group( function(){
        Route::get('/exam/create','create')->name("exam.create");
        Route::post('/exam','store')->name("exam.store");
        Route::get('/exam','index')->name("exam.list");
        Route::get('/exam/{id}/edit', 'edit')->name("exam.edit");
        Route::put('/exam/{id}', 'update')->name("exam.update");
        Route::delete('/exam/{id}', 'destroy')->name("exam.delete");
    });

    Route::controller(FeedbackEmailController::class)->group( function(){
        Route::get('/feedbackEmail/create','create')->name("feedbackEmail.create");
        Route::post('/feedbackEmail','store')->name("feedbackEmail.store");
        Route::get('/feedbackEmail','index')->name("feedbackEmail.list");
        Route::get('/feedbackEmail/{id}/edit', 'edit')->name("feedbackEmail.edit");
        Route::put('/feedbackEmail/{id}', 'update')->name("feedbackEmail.update");
        Route::delete('/feedbackEmail/{id}', 'destroy')->name("feedbackEmail.delete");
    });

    Route::controller(DashboardController::class)->group( function(){
        Route::get('/home','index')->name('admin.dashboard');
        Route::post('/piechart','piechart')->name('admin.dashboard.piechart');
        Route::post('/linechart','linechart')->name('admin.dashboard.linechart');
        Route::post('/year-month/absentees','yearlyMonthAbsentees')->name('admin.dashboard.yearlyMonthAbsentees');
    });

    //Super-Admin
    Route::controller(AttendanceController::class)->group( function(){
        Route::get('/attendance','list')->name('attendance.list');
        Route::get('/attendance/edit/{id}','edit')->name('attendance.edit');
        Route::put('/attendance','update')->name('attendance.update');
        // Route::get('/attendance/{teacher_group_subject_id}','index')->name('attendance.create');
        Route::get('/attendanceReport','sendDailyReportMail');
    });



    Route::controller(UserController::class)->group( function(){
        Route::get('/user/create','create')->name("user.create");
        Route::post('/user','store')->name("user.store");
        Route::get('/user','index')->name("user.list");
        Route::get('/user/{id}/edit', 'edit')->name("user.edit");
        Route::put('/user/{id}', 'update')->name("user.update");
        Route::delete('/user/{id}', 'destroy')->name("user.delete");

        Route::get('/user/bulk','createBulk')->name('user.create.bulk');
        Route::post('/user/bulk','storeBulk')->name('user.store.bulk');
    });

     Route::controller(StreamController::class)->group( function(){
        Route::get('/stream/create','create')->name("stream.create");
        Route::post('/stream','store')->name("stream.store");
        Route::get('/stream','index')->name("stream.list");
        Route::get('/stream/{id}/edit', 'edit')->name("stream.edit");
        Route::put('/stream/{id}', 'update')->name("stream.update");
        Route::delete('/stream/{id}', 'destroy')->name("stream.delete");
    });

    Route::controller(BatchController::class)->group( function(){
        Route::get('/batch/create','create')->name("batch.create");
        Route::post('/batch','store')->name("batch.store");
        Route::get('/batch','index')->name("batch.list");
        Route::get('/batch/{id}/edit', 'edit')->name("batch.edit");
        Route::put('/batch/{id}', 'update')->name("batch.update");
        Route::delete('/batch/{id}', 'destroy')->name("batch.delete");
        Route::delete('/batch/forceDelete/{id}', 'forceDelete')->name('batch.forceDelete');
        Route::get('/batch/semesterEndReport/{id}','semesterEndReport')->name('batch.semesterEndReport');

        //batch-livesearch
        // Route::get('/batch/search','search')->name("batch.search");
    });

    Route::controller(StudentController::class)->group( function(){
        Route::get('/student/create','create')->name('student.create');
        Route::post('/student','store')->name('student.store');
        Route::get('/student','index')->name('student.list');
        Route::get('/student/{id}/edit','edit')->name('student.edit');
        Route::put('/student/{id}', 'update')->name('student.update');
        Route::delete('/student/{id}','destroy')->name('student.delete');
        Route::delete('/student/{id}/restore','restore')->name('student.restore');
        Route::post('/student/bulkUpload','bulkUpload')->name('student.bulkUpload');
        Route::get('/student/get-upload-bulk','getBulkUpload')->name('student.getUploadBulk');

        Route::get('/student/bulk','createBulk')->name('student.create.bulk');
        Route::post('/student/bulk','storeBulk')->name('student.store.bulk');

        //batch-livesearch
        // Route::get('/student/search','search')->name("student.search");
    });

    Route::controller(SubjectController::class)->group( function(){
        Route::get('/subject/create','create')->name('subject.create');
        Route::post('/subject','store')->name('subject.store');
        Route::get('/subject','index')->name('subject.list');
        Route::get('/subject/{id}/edit','edit')->name('subject.edit');
        Route::put('/subject/{id}', 'update')->name('subject.update');
        Route::delete('/subject/{id}','destroy')->name('subject.delete');
        Route::get('/subject/search', 'search')->name('subject.search');

        Route::get('/subject/bulk','createBulk')->name('subject.create.bulk');
        Route::post('/subject/bulk','storeBulk')->name('subject.store.bulk');
    });


    //view route for bulk upload
    Route::view('/student/bulkUpload', 'admin.student.bulkUpload');

    Route::controller(GroupController::class)->group( function(){
        Route::get('/group/create','create')->name('group.create');
        Route::post('/group','store')->name('group.store');
        Route::get('/group','index')->name('group.list');
        Route::get('/group/{id}/edit','edit')->name('group.edit');
        Route::put('/group/{id}', 'update')->name('group.update');
        Route::delete('/group/{id}','destroy')->name('group.delete');
    });


    Route::controller(SubjectGroupAssignController::class)->group( function(){
        Route::get('subject-group/create','create')->name('subject_group_assign.create');
        Route::post('/subject-group','store')->name('subject_group_assign.store');
        Route::get('/subject-group','index')->name('subject_group_assign.list');
        Route::get('/subject-group/{id}/edit','edit')->name('subject_group_assign.edit');
        Route::put('/subject-group/{id}', 'update')->name('subject_group_assign.update');
        Route::delete('/subject-group/{id}','destroy')->name('subject_group_assign.delete');
    });

    Route::controller(StudentGroupAssignController::class)->group( function(){
        Route::get('/student-group-assign/create','create')->name('student-group-assign.create');
        Route::post('/student-group-assign','store')->name('student-group-assign.store');
        Route::get('/student-group-assign','index')->name('student-group-assign.list');
        Route::get('/student-group-assign/{id}/edit','edit')->name('student-group-assign.edit');
        Route::put('/student-group-assign/{id}', 'update')->name('student-group-assign.update');
        Route::delete('/student-group-assign/{id}','destroy')->name('student-group-assign.delete');
    });

    Route::controller(SearchController::class)->group( function(){
        //livesearch
        Route::get('/student/search','searchStudent')->name("student.search");
        Route::get('/batch/search','searchBatch')->name("batch.search");
        Route::get('/group/search','searchGroup')->name("group.search");
    });

    Route::controller(FeedbackController::class)->group( function(){
        Route::get('/feedback/create','create')->name('feedback.create');
        Route::post('/feedback','store')->name('feedback.store');
        Route::get('/feedback','index')->name('feedback.list');
        // Route::get('/feedback/teacher','teacherIndex')->name('feedback.teacher.list');
        Route::get('/feedback/{id}/edit','edit')->name('feedback.edit');
        Route::put('/feedback/{id}', 'update')->name('feedback.update');
        Route::delete('/feedback/{id}','destroy')->name('feedback.delete');
        Route::get('/feedback/download/{id}','download')->name('feedback.download');

        Route::put('/feedback/accept/{id}','accept')->name('feedback.accept');
        Route::put('/feedback/reject/{id}', 'reject')->name('feedback.reject');
        // Route::put('/feedback/reissue/{id}','reissue')->name('feedback.reissue');

    });


    Route::controller(AttendancePermissionController::class)->group( function(){
        Route::get('/attendance-permission/create','create')->name('attendance-permission.create');
        Route::post('/attendance-permission','store')->name('attendance-permission.store');
        Route::get('/attendance-permission','index')->name('attendance-permission.list');
        Route::get('/attendance-permission/{id}/edit','edit')->name('attendance-permission.edit');
        Route::put('/attendance-permission/{id}', 'update')->name('attendance-permission.update');
        Route::delete('/attendance-permission/{id}','destroy')->name('attendance-permission.delete');
    });


    Route::controller(TeacherSubjectGroupAssignController::class)->group( function() {
        Route::get('teacher-subject-group/create','create')->name('teacher_subject_group_assign.create');
        Route::post('/teacher-subject-group','store')->name('teacher_subject_group_assign.store');
        Route::get('/teacher-subject-group','index')->name('teacher_subject_group_assign.list');
        Route::get('/teacher-subject-group/{id}/{teacherId}/edit','edit')->name('teacher_subject_group_assign.edit');
        // Route::put('/teacher-subject-group/{id}', 'update')->name('teacher_subject_group_assign.update');
        Route::put('/teacher-subject-group/{groupSubjectTeacherId}/{groupSubjectId}', 'update')->name('teacher_subject_group_assign.update');
        Route::delete('/teacher-subject-group/{id}','destroy')->name('teacher_subject_group_assign.delete');

    });

    Route::controller(ReportController::class)->group( function(){
        Route::get('/report','index')->name('report.list');
        Route::get('/report/search', 'search')->name('report.search');
        Route::get('/report/batchSearch', 'batchSearch')->name('report.batchSearch');
        Route::post('/download/report', 'download')->name('report.download');

    });

});

Route::middleware('student')->name('student.')->group( function(){
    Route::prefix('student')->controller(StudentViewController::class)->group( function(){
        Route::get('/dashboard','dashboard')->name('dashboard');
        Route::get('/attendance-report-search','search')->name('attendance-search');
        Route::post('/piechart', 'piechart')->name('attendance-piechart');
    });
});

Route::controller(ReportController::class)->group( function(){
    Route::get('new/report','index')->name('report.list');
    Route::get('new/report/search', 'search')->name('report.search');
    Route::get('new/report/batchSearch', 'batchSearch')->name('report.batchSearch');
    Route::post('new/download/report', 'download')->name('report.download');
});
