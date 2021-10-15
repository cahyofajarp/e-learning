<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes(['register' => false]);

// Route::get('/', function () {
//     return view('auth.login');
// });

// Route::get('/',[\App\Http\Controllers\Auth\LoginController::class,'redirectTo']);

Route::get('/',function(){
    if(auth()->user()){
        if(auth()->user()->roles == 'admin'){
            return redirect()->route('admin.home');
        }
        else if(auth()->user()->roles == 'guru'){
            return redirect()->route('teacher.home');
        }
        else if(auth()->user()->roles == 'siswa'){
            return redirect()->route('student.home');
        }
    }
    else{
        return view('auth.login');
    }
});

Route::prefix('admin')->group(function() {
    Route::group(['middleware' => ['auth','CheckRole:admin']],function() {

        Route::get('/home', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.home');
        //---------------------------------------------------------------------------------------
        Route::get('/siswa',[App\Http\Controllers\Admin\StudentController::class,'index'])
        ->name('admin.student.index');

        Route::post('/siswa/store',[App\Http\Controllers\Admin\StudentController::class,'store'])
        ->name('admin.student.store');

        Route::delete('/siswa/{student}/destroy',[App\Http\Controllers\Admin\StudentController::class,'destroy'])
        ->name('admin.student.destroy');

        Route::put('/siswa/{student}/update',[App\Http\Controllers\Admin\StudentController::class,'update'])
        ->name('admin.student.update');

        Route::get('/siswa/{student}/edit',[App\Http\Controllers\Admin\StudentController::class,'edit'])
        ->name('admin.student.edit');

        Route::get('/siswa/pdf/export',[App\Http\Controllers\Admin\StudentController::class,'exportPdf'])
        ->name('admin.student.exportPdf');

        Route::post('/siswa/import/store',[App\Http\Controllers\Admin\StudentController::class,'importExcel'])
        ->name('admin.student.import');

        //------------------------------------------------------------------------------------------------------------------

        Route::get('/guru',[App\Http\Controllers\Admin\TeacherController::class,'index'])
        ->name('admin.teacher.index');
        
        Route::post('/guru/store',[App\Http\Controllers\Admin\TeacherController::class,'store'])
        ->name('admin.teacher.store');

        Route::post('/guru/importExcel',[App\Http\Controllers\Admin\TeacherController::class,'importExcel'])
        ->name('admin.teacher.importExcel');
        
        Route::put('/guru/{teacher}/update',[App\Http\Controllers\Admin\TeacherController::class,'update'])
        ->name('admin.teacher.update');

        Route::get('/guru/{teacher}/edit',[App\Http\Controllers\Admin\TeacherController::class,'edit'])
        ->name('admin.teacher.edit');

        Route::delete('/guru/{teacher}/destroy',[App\Http\Controllers\Admin\TeacherController::class,'destroy'])
        ->name('admin.teacher.destroy');

        //-------------------------------------------------------------------------------------------------
        Route::get('/levelclass',[App\Http\Controllers\Admin\LevelclassController::class,'index'])
        ->name('admin.levelclass.index');

        Route::post('/levelclass',[App\Http\Controllers\Admin\LevelclassController::class,'store'])
        ->name('admin.levelclass.store');

        Route::get('/levelclass/{levelclass}/edit',[App\Http\Controllers\Admin\LevelclassController::class,'edit'])
        ->name('admin.levelclass.edit');

        Route::put('/levelclass/{levelclass}/update',[App\Http\Controllers\Admin\LevelclassController::class,'update'])
        ->name('admin.levelclass.update');

        Route::delete('/levelclass/{levelclass}/destroy',[App\Http\Controllers\Admin\LevelclassController::class,'destroy'])
        ->name('admin.levelclass.destroy');

        //-------------------------------------------------------------------------------------------------

        Route::get('/classroom',[App\Http\Controllers\Admin\ClassroomController::class,'index'])
        ->name('admin.classroom.index');

        Route::get('/classroom/{classroom}/edit',[App\Http\Controllers\Admin\ClassroomController::class,'edit'])
        ->name('admin.classroom.edit');

        Route::post('/classroom/store',[App\Http\Controllers\Admin\ClassroomController::class,'store'])
        ->name('admin.classroom.store');

        Route::delete('/classroom/{classroom}/destroy',[App\Http\Controllers\Admin\ClassroomController::class,'destroy'])
        ->name('admin.classroom.destroy');

        Route::put('/classroom/{classroom}/update',[App\Http\Controllers\Admin\ClassroomController::class,'update'])
        ->name('admin.classroom.update');

        //-------------------------------------------------------------------------------------------------------
        
        Route::get('/lesson',[App\Http\Controllers\Admin\LessonController::class,'index'])
        ->name('admin.lesson.index');

        Route::get('/lesson/{classroom:slug}/classroom',[App\Http\Controllers\Admin\LessonController::class,'classroomLesson'])
        ->name('admin.lesson.classroomLesson');

        Route::get('/lesson/{classroom:slug}/getRandomCode',[App\Http\Controllers\Admin\LessonController::class,'getRandomCode'])
        ->name('admin.lesson.getRandomCode');

        Route::post('/lesson/{classroom:slug}/classroom/store',[App\Http\Controllers\Admin\LessonController::class,'store'])
        ->name('admin.lesson.store');

        Route::post('/lesson/{classroom:slug}/classroom/addLessonAutomatic',[App\Http\Controllers\Admin\LessonController::class,'addLessonAutomatic'])
        ->name('admin.lesson.addLessonAutomatic');

        Route::delete('/lesson/{classroom:slug}/classroom/{lesson}/destroy',[App\Http\Controllers\Admin\LessonController::class,'destroy'])
        ->name('admin.lesson.destroy');

        Route::put('/lesson/{classroom:slug}/classroom/{lesson}/update',[App\Http\Controllers\Admin\LessonController::class,'update'])
        ->name('admin.lesson.update');
        
        Route::get('/lesson/{classroom:slug}/classroom/{lesson}/edit',[App\Http\Controllers\Admin\LessonController::class,'edit'])
        ->name('admin.lesson.edit');

        // -------------------------------------------------------------------------------------------------------
       
        Route::get('/lessonTeacher',[App\Http\Controllers\Admin\LessonTeacherController::class,'index'])
        ->name('admin.lessonTeacher.index');

        Route::get('/lessonTeacher/{classroom:slug}/create',[App\Http\Controllers\Admin\LessonTeacherController::class,'create'])
        ->name('admin.lessonTeacher.create');

        Route::post('/lessonTeacher/{classroom:slug}/{lesson}/store',[App\Http\Controllers\Admin\LessonTeacherController::class,'store'])
        ->name('admin.lessonTeacher.store');

        // -------------------------------------------------------------------------------------------------------------
        

        Route::get('/test',[App\Http\Controllers\Admin\TestController::class,'index'])
        ->name('admin.test.index');


        Route::get('/test/{classroom:slug}/classroom/lesson',[App\Http\Controllers\Admin\TestController::class,'test'])
        ->name('admin.test.test');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testcode/{code?}',[App\Http\Controllers\Admin\TestController::class,'testMiddleware'])
        ->name('admin.test.test.testMiddleware');
        
        Route::post('/test/{classroom:slug}/classroom/lesson/{lesson}',[App\Http\Controllers\Admin\TestController::class,'checkCode'])
        ->name('admin.test.checkCode');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate',[App\Http\Controllers\Admin\TestController::class,'testCreate'])
        // ->middleware('testMiddleware')
        ->name('admin.test.testCreate');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/create',[App\Http\Controllers\Admin\TestController::class,'testCreateView'])
        ->name('admin.test.test.testCreate.create');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/{test}/classCheck',[App\Http\Controllers\Admin\TestController::class,'classCheck'])
        ->name('admin.test.test.classTestCheck');

        Route::post('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/store',[App\Http\Controllers\Admin\TestController::class,'store'])
        ->name('admin.test.test.testCreate.store');

        Route::delete('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/destroy',[App\Http\Controllers\Admin\TestController::class,'destroy'])
        ->name('admin.test.test.testCreate.destroy');
        
        // ------------------------------------------------------------------------------------------------------------

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/question',[App\Http\Controllers\Admin\QuestionController::class,'index'])
        ->name('admin.test.test.testCreate.question');
        
        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/questionCreate',[App\Http\Controllers\Admin\QuestionController::class,'create'])
        ->name('admin.test.test.testCreate.Questioncreate');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/questionCheck',[App\Http\Controllers\Admin\QuestionController::class,'questionCheck'])
        ->name('admin.test.test.question.questionCheck');


        Route::post('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/questionCreate/store',[App\Http\Controllers\Admin\QuestionController::class,'store'])
        ->name('admin.test.question.store');

        Route::delete('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/questionCreate/{question}/destroy',[App\Http\Controllers\Admin\QuestionController::class,'destroy'])
        ->name('admin.test.question.destroy');

        Route::post('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/question/destroySelect',[App\Http\Controllers\Admin\QuestionController::class,'destroySelect'])
        ->name('admin.test.test.testCreate.destroySelect');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/question/view',[App\Http\Controllers\Admin\QuestionController::class,'questionView'])
        ->name('admin.test.test.questionView');

        Route::post('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/question/import',[App\Http\Controllers\Admin\QuestionController::class,'importExcelQuestion'])
        ->name('admin.test.test.importExcelQuestion');
        //-------------------------------------------------------------------------------------------------------

        Route::get('/studentWork',[App\Http\Controllers\Admin\StudentWork::class,'classroom'])
        ->name('admin.studentWork.classroom');

        Route::get('/studentWork/{classroom:slug}/lesson',[App\Http\Controllers\Admin\StudentWork::class,'lesson'])
        ->name('admin.studentWork.lesson');

        
        Route::post('/student-work/{classroom:slug}/classroom/lesson/{lesson}',[App\Http\Controllers\Admin\StudentWork::class,'checkCode'])
        ->name('admin.studentWork.checkCode');

        Route::get('/student-work/{classroom:slug}/classroom/lesson/{lesson:slug}/work',[App\Http\Controllers\Admin\StudentWork::class,'index'])
        ->name('admin.studentWork.index');

        Route::get('/student-work/{classroom:slug}/classroom/lesson/{lesson:slug}/create',[App\Http\Controllers\Admin\StudentWork::class,'create'])
        ->name('admin.studentWork.create');

        Route::post('/student-work/{classroom:slug}/classroom/lesson/{lesson:slug}/store',[App\Http\Controllers\Admin\StudentWork::class,'store'])
        ->name('admin.studentWork.store');


        Route::delete('/student-work/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/destroy',[App\Http\Controllers\Admin\StudentWork::class,'destroy'])
        ->name('admin.studentWork.destroy');

        
        Route::get('/student-work/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/preview',[App\Http\Controllers\Admin\StudentWork::class,'preview'])
        ->name('admin.studentWork.preview');

        Route::get('/student-work/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/edit',[App\Http\Controllers\Admin\StudentWork::class,'edit'])
        ->name('admin.studentWork.edit');

        Route::put('/student-work/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/update',[App\Http\Controllers\Admin\StudentWork::class,'update'])
        ->name('admin.studentWork.update');
       

    });

});

Route::prefix('guru')->group(function() {
    Route::group(['middleware' => ['auth','CheckRole:guru']],function() {
        
        Route::get('/home', [App\Http\Controllers\Teacher\DashboardController::class,'index'])->name('teacher.home');

        Route::get('/work/data-siswa/statistik-student',[App\Http\Controllers\Teacher\DashboardController::class,'classroomWork'])
        ->name('teacher.classroom');

        Route::get('/work/data-siswa/statistik-student/{classroom}/classroom',[App\Http\Controllers\Teacher\DashboardController::class,'lessonWork'])
        ->name('teacher.statistik.lesson');

        Route::get('/work/data-siswa/statistik-student/{classroom}/classroom/{lesson}/lesson',[App\Http\Controllers\Teacher\DashboardController::class,'studentWork'])
        ->name('teacher.statistik.student');

        Route::get('/work/data-siswa/statistik-student/{classroom}/classroom/{lesson}/lesson/{student}/student/statistik',[App\Http\Controllers\Teacher\DashboardController::class,'statistikWork'])
        ->name('teacher.statistik.student.statistik');

        Route::get('/work/data-siswa/statistik-student/{classroom}/classroom/{lesson}/lesson/{student}/student/statistik/export',[App\Http\Controllers\Teacher\DashboardController::class,'exportWorkStudent'])
        ->name('teacher.statistik.student.exportWorkStudent');
        
        
        Route::get('/work/data-siswa/statistik-student/{classroom}/classroom/{lesson}/lesson/{work}/work/preview',[App\Http\Controllers\Teacher\DashboardController::class,'previewWork'])
        ->name('teacher.statistik.student.work.preview');

        //-------------------------------------------------------------------------------------------------------------------------------------------------------------

        Route::get('/test/data-siswa/statistik-student',[App\Http\Controllers\Teacher\DashboardController::class,'classroomTest'])
        ->name('teacher.test.statistik.classroom');

        Route::get('/test/data-siswa/statistik-student/{classroom}/classroom',[App\Http\Controllers\Teacher\DashboardController::class,'lessonTest'])
        ->name('teacher.test.statistik.lesson');

        Route::get('/test/data-siswa/statistik-student/{classroom}/classroom/{lesson}/lesson',[App\Http\Controllers\Teacher\DashboardController::class,'studentTest'])
        ->name('teacher.test.statistik.student');

        Route::get('/test/data-siswa/statistik-student/{classroom}/classroom/{lesson}/lesson/{student}/student/statistik',[App\Http\Controllers\Teacher\DashboardController::class,'statistiTest'])
        ->name('teacher.test.statistik.student-statistik');

        Route::get('/student-management', [App\Http\Controllers\Teacher\StudentController::class,'index'])->name('teacher.student');
        
        Route::get('/student-management/classroom/{classroom:slug}/student', [App\Http\Controllers\Teacher\StudentController::class,'studentAll'])->name('teacher.student.studentAll');
        
        Route::get('/student-management/classroom/{classroom:slug}/student/{student:slug}', [App\Http\Controllers\Teacher\StudentController::class,'statistik'])->name('teacher.student.statistik');
        
        // Route::post('/send-messageTo-admin', [App\Http\Controllers\Teacher\DashboardController::class,'sendMessage'])->name('guru.sendMessageToAdmin');
        
        //-------------------------------------------------------------------------------------------------------------------------------------------

        Route::get('/test-management', [App\Http\Controllers\Teacher\TestController::class,'classroom'])
        ->name('teacher.test.classroom');
        
        Route::get('/test-management/classroom/{classroom:slug}', [App\Http\Controllers\Teacher\TestController::class,'lesson'])
        ->name('teacher.test.lesson');

        Route::post('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/checkCode', [App\Http\Controllers\Teacher\TestController::class,'checkCode'])
        ->name('teacher.lesson.checkCode');

        Route::get('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test', [App\Http\Controllers\Teacher\TestController::class,'indexTest'])
        ->name('teacher.lesson.test.index');

        Route::get('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test/create', [App\Http\Controllers\Teacher\TestController::class,'create'])
        ->name('teacher.lesson.test.create');

        Route::get('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test/{test}/edit', [App\Http\Controllers\Teacher\TestController::class,'edit'])
        ->name('teacher.lesson.test.edit');

        Route::put('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test/{test}/update', [App\Http\Controllers\Teacher\TestController::class,'update'])
        ->name('teacher.lesson.test.update');

        Route::get('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test/{test}/chekClass', [App\Http\Controllers\Teacher\TestController::class,'checkClass'])
        ->name('teacher.lesson.test.checkClass');

        Route::post('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test/store', [App\Http\Controllers\Teacher\TestController::class,'store'])
        ->name('teacher.lesson.test.store');
        
        Route::delete('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test/{test}/destroy', [App\Http\Controllers\Teacher\TestController::class,'destroy'])
        ->name('teacher.lesson.test.destroy');

        //------------------------------------------------------------------------------------------------------------

        Route::get('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test/{test}/question', [App\Http\Controllers\Teacher\QuestionController::class,'question'])
        ->name('teacher.lesson.test.question.create');
        
        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/questionCreate',[App\Http\Controllers\Teacher\QuestionController::class,'Questioncreate'])
        ->name('teacher.lesson.test.question.Questioncreate');

        Route::post('/test-management/classroom/{classroom:slug}/lesson/{lesson:code}/test/{test}/question/importExcelQuestion', [App\Http\Controllers\Teacher\QuestionController::class,'importExcelQuestion'])
        ->name('teacher.lesson.test.question.importExcelQuestion');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/questionCheck',[App\Http\Controllers\Teacher\QuestionController::class,'questionCheck'])
        ->name('teacher.lesson.test.question.questionCheck');

        Route::post('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/questionCreate/store',[App\Http\Controllers\Teacher\QuestionController::class,'store'])
        ->name('teacher.lesson.test.question.store');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/question/{question}/{no}/edit',[App\Http\Controllers\Teacher\QuestionController::class,'edit'])
        ->name('teacher.lesson.test.question.edit');

        Route::put('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/question/{question}/{no}/update',[App\Http\Controllers\Teacher\QuestionController::class,'update'])
        ->name('teacher.lesson.test.question.update');

        Route::delete('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/questionCreate/{question}/destroy',[App\Http\Controllers\Teacher\QuestionController::class,'destroy'])
        ->name('teacher.lesson.test.question.destroy');

        Route::post('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/question/destroySelect',[App\Http\Controllers\Teacher\QuestionController::class,'destroySelect'])
        ->name('teacher.lesson.test.question.destroySelect');

        Route::get('/test/{classroom:slug}/classroom/lesson/{lesson:slug}/testCreate/{test}/question/view',[App\Http\Controllers\Teacher\QuestionController::class,'questionView'])
        ->name('teacher.lesson.test.question.questionView');

        //----------------------------------------------------------------------------------------------------------------------------------------------------------

        Route::get('/work/student/classroom', [App\Http\Controllers\Teacher\WorkController::class,'classroom'])
        ->name('teacher.work.student.classroom');

        Route::get('/work/student/classroom/{classroom:slug}', [App\Http\Controllers\Teacher\WorkController::class,'lesson'])
        ->name('teacher.work.student.lesson');

        Route::get('/work/student/classroom/{classroom:slug}/lesson/{lesson:slug}', [App\Http\Controllers\Teacher\WorkController::class,'work'])
        ->name('teacher.work.student.work');

        Route::post('/work/student/classroom/{classroom:slug}/lesson/{lesson:slug}/checkCode', [App\Http\Controllers\Teacher\WorkController::class,'checkCode'])
        ->name('teacher.work.student.checkCode');

        
        Route::get('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/preview',[App\Http\Controllers\Teacher\WorkController::class,'preview'])
        ->name('teacher.work.student.preview');
        
        Route::get('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/create/work',[App\Http\Controllers\Teacher\WorkController::class,'create'])
        ->name('teacher.work.student.create');

        Route::post('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/create/store',[App\Http\Controllers\Teacher\WorkController::class,'store'])
        ->name('teacher.work.student.store');

        Route::get('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/edit',[App\Http\Controllers\Teacher\WorkController::class,'edit'])
        ->name('teacher.work.student.edit');

        Route::get('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/add-score',[App\Http\Controllers\Teacher\WorkController::class,'score'])
        ->name('teacher.work.student.score');

        Route::put('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/update',[App\Http\Controllers\Teacher\WorkController::class,'update'])
        ->name('teacher.work.student.update');

        Route::delete('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/destroy',[App\Http\Controllers\Teacher\WorkController::class,'destroy'])
        ->name('teacher.work.student.destroy');

        
        Route::get('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/{student}/student',[App\Http\Controllers\Teacher\WorkController::class,'giveScore'])
        ->name('teacher.work.student.giveScore');

        Route::post('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/{student}/student/score/create',[App\Http\Controllers\Teacher\WorkController::class,'scoreCreate'])
        ->name('teacher.work.student.score.create');
    
        Route::get('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/{student}/student/download',[App\Http\Controllers\Teacher\WorkController::class,'workFileDownload'])
        ->name('teacher.work.student.file.download');

        Route::get('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/ranking',[App\Http\Controllers\Teacher\WorkController::class,'ranking'])
        ->name('teacher.work.student.ranking');
        
         //--------------------------- export data ---------------------------------------------------------------

         Route::get('/work/student/{classroom:slug}/classroom/lesson/{lesson:slug}/{work}/work/ranking/export-all-student-work',[App\Http\Controllers\Teacher\WorkController::class,'exportAllResultStudentWork'])
         ->name('teacher.work.student.exportAllResultStudentWork');
         
 
    });

});


Route::prefix('siswa')->group(function() {
    Route::group(['middleware' => ['auth','CheckRole:siswa']],function() {
        
        
        Route::get('/home',[App\Http\Controllers\Student\DashboardController::class,'dashboard'])
        ->name('student.home');

        Route::get('/statistik/all',[App\Http\Controllers\Student\StatistikController::class,'all'])
        ->name('student.all.statistik');

        Route::get('/statistik/all/lesson/{lesson}',[App\Http\Controllers\Student\StatistikController::class,'lesson'])
        ->name('student.lesson.statistik');

        Route::post('/notification/{id}',[App\Http\Controllers\Student\DashboardController::class,'notification'])
        ->name('notification');
    

        Route::get('/material/lesson',[App\Http\Controllers\Student\WorkController::class,'lesson'])
        ->name('student.material.lesson');
        
        Route::get('/material/{lesson:slug}/lesson',[App\Http\Controllers\Student\WorkController::class,'index'])
        ->name('student.material.index');

        Route::get('/material/{lesson:slug}/lesson/{work}/preview',[App\Http\Controllers\Student\WorkController::class,'preview'])
        ->name('student.material.preview');

        //----------------------------------------------------------------------------------------------
        
        Route::get('/work/lesson',[App\Http\Controllers\Student\WorkController::class,'lessonWork'])
        ->name('student.work.lesson');

        Route::get('/work/{lesson:slug}/lesson',[App\Http\Controllers\Student\WorkController::class,'indexWork'])
        ->name('student.work.index');

        Route::post('/work/automaticScore',[App\Http\Controllers\Student\WorkController::class,'automaticScoreScheduler'])
        ->name('student.work.automaticScoreScheduler');

        Route::get('/work/{lesson:slug}/lesson/{work}/work/preview',[App\Http\Controllers\Student\WorkController::class,'previewWork'])
        ->name('student.work.preview');

        Route::get('/work/{lesson:slug}/lesson/{work}/work/nilai',[App\Http\Controllers\Student\WorkController::class,'nilai'])
        ->name('student.work.nilai');

        Route::post('/work/{lesson:slug}/lesson/{work}/work/sendWork',[App\Http\Controllers\Student\WorkController::class,'sendWork'])
        ->name('student.work.sendWork');

        Route::get('/test/lesson',[App\Http\Controllers\Student\TestController::class,'lesson'])
        ->name('student.test.lesson');

        Route::get('/test/lesson/{lesson}',[App\Http\Controllers\Student\TestController::class,'index'])
        ->name('student.test.index');

        Route::post('/test/lesson/{lesson}/classroom/{classroom}/test/{test}/checkCode',[App\Http\Controllers\Student\TestController::class,'checkCode'])
        ->name('student.test.checkCode');

        Route::get('/test/lesson/{lesson}/classroom/{classroom}/test/{test}/start-test',[App\Http\Controllers\Student\TestController::class,'startTest'])
        ->name('student.test.startTest');

        Route::get('/test/lesson/{lesson}/classroom/{classroom}/test/{test}/start',[App\Http\Controllers\Student\TestController::class,'start'])
        ->name('student.test.startTest.start');

        Route::post('/test/lesson/{lesson}/classroom/{classroom}/test/{test}/data-test',[App\Http\Controllers\Student\TestController::class,'middlewareTest'])
        ->name('student.test.startTest.middleware');

        Route::post('/test/lesson/{lesson}/classroom/{classroom}/test/{test}/{result}start/store',[App\Http\Controllers\Student\TestController::class,'store'])
        ->name('student.test.startTest.store');

        Route::get('/test/lesson/{lesson}/classroom/{classroom}/test/{test}/result/{result}',[App\Http\Controllers\Student\TestController::class,'result'])
        ->name('student.test.startTest.result');

        Route::get('/test/lesson/{lesson}/classroom/{classroom}/test/{test}/result/{result}/preview',[App\Http\Controllers\Student\TestController::class,'preview'])
        ->name('student.test.startTest.preview');

        Route::get('/test/timeEnd',[App\Http\Controllers\Student\TestController::class,'testEndTime'])
        ->name('student.test.testEndTime');

        Route::get('/markAsRead',function(){
            auth()->user()->unreadNotifications->markAsRead();
            return response()->json(['data' => auth()->user()->unreadNotifications]);
        });

        Route::get('/notif/read',function() {
            return response()->json(['data' => auth()->user()->notifications]);
        });

        Route::get('/notif/unread',function() {
            return response()->json(['data' => auth()->user()->unreadNotifications]);
        });

        //------------------------------------------------------------------------------------------

    
        
    });

});

