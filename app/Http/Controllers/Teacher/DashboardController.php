<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\statistikExport;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\LessonTeacher;
use App\Models\Work;
use App\Models\Student;
use App\Models\Test;
use App\Models\Materialwork;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{   
    
    public function index()
    {   
        $lesson = LessonTeacher::where('teacher_id',auth()->user()->teacher_id)->with(['classroom'])
                    ->count('lesson_id');

        $classroom = LessonTeacher::where('teacher_id',auth()->user()->teacher_id)->with(['classroom'])
        ->groupBy('classroom_id')->get()->count();

        
        $classroom_id = LessonTeacher::where('teacher_id',auth()->user()->teacher_id)
                ->pluck('classroom_id');

        $student = Student::whereIn('classroom_id',$classroom_id)->count();
            
        $test = Test::where('teacher_id',auth()->user()->teacher_id)->count();

       
        return view('pages.teacher.dashboard.dashboard',compact(
            'lesson',
            'classroom',
            'student',
            'test'
        ));

    
        
    }

    public function classroomWork()
    {
        $lessonTeacher = LessonTeacher::with('classroom')
        ->where('teacher_id',auth()->user()->teacher_id)
        ->groupBy('classroom_id')
        ->get();

        return view('pages.teacher.dashboard.statisitk',compact(
            'lessonTeacher'
        ));
    }

    public function lessonWork(Classroom $classroom)
    {
        $lessons= lesson::with(['teachers'])->whereHas('teachers',function($q) use ($classroom){

            $q->where('teacher_id',auth()->user()->teacher_id)->where('classroom_id',$classroom->id);
            
        })->get();

        $workCountLesson  = Classroom::with(['works'])->where('id',$classroom->id)->first();

        return view('pages.teacher.dashboard.lesson',compact(
            'classroom',
            'lessons',
            'workCountLesson'
        ));
    }

    public function studentWork(Classroom $classroom ,lesson $lesson)
    {
        return view('pages.teacher.dashboard.student',compact(
            'classroom',
            'lesson'
        ));
    }

    public function statistikWork(Request $request,Classroom $classroom ,lesson $lesson,Student $student)
    {   
        
        $from = date($request->from_date);
        $to = date($request->to_date);

        
        //---------------- Data Semua tugas -------------------------------------------
        $materialwork = Materialwork::with(['work','students','answerwork'])

        ->whereHas('work',function($q) use($lesson){
            $q->where('type','TUGAS')->where('lesson_id',$lesson->id);
        })
        ->whereHas('students',function($q) use ($student){
            $q->where('students.id',$student->id);
        })
        ->get();
        
        //---------------- Data Semua tugas -------------------------------------------


        // ------------- search by from date to date ------------------------------------------------
       if($from && $to || $from){
            $materialwork = Materialwork::with(['work','students','answerwork'])
            ->whereHas('work',function($q) use ($from,$to,$lesson){
                    
                    $q->whereBetween('created_at',[$from." 00:00:00",$to." 23:59:59"])
                        ->where('type','TUGAS')
                        ->where('lesson_id',$lesson->id)
                        ->orWhereBetween('created_at',[$from." 00:00:00",$from." 23:59:59"]);
                    
            })
            ->whereHas('students',function($q) use ($student){
                $q->where('students.id',$student->id);
            })
            ->get();
       }
       
        // ------------- end search by from date to date ------------------------------------------------

        
         if($request->ajax()){
             
        // --------------------- Rata rata siswa -------------------------------------------------------/

            $materialworkStatistik =  Materialwork::with(['work','students','answerwork'])
            ->whereHas('students',function($q) use ($student){
                $q->where('students.id',$student->id);
            })
            ->whereHas('work',function($q) use ($lesson){
                $q->where('type','TUGAS')->where('lesson_id',$lesson->id);
            })
            ->whereYear('created_at', '=', $request->searchByYear ?  $request->searchByYear : date('Y'))
            ->get()
            ->groupBy(function($date){
                return \Carbon\Carbon::parse($date->created_at)->format('m');
            });
            
                
            
            $dataAvg = array();
            $no =1;
            
            foreach($materialworkStatistik as $key => $val){
                
                $count = 0;
                
                $scores = 0;
                foreach($val as $data){
                    if($data->answerwork){
                        $scores += $data->answerwork->score;
                        $count += $data->answerwork()->count();
                    }
                   
                   else{
                       $scores += 0;
                       $count += 0;
                   }
                }

            

                $avg = number_format((float)$scores / $count, 1, '.', '');

                $dataAvg[(int) $key -  1] = $avg; 
            }

            $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agus','Sep','Okt','Nov','Des'];

            for($i = 0; $i < 12; $i++){

                if(!empty($dataAvg[$i])){
                    $monthArray[$i] = ['month' => $months[$i],'avg' => $dataAvg[$i]];
                }
                else{
                    $monthArray[$i] = ['month' => $months[$i],'avg' => 0];
                }
            }
            // --------------------- Rata rata siswa -------------------------------------------------------/
                return response()->json([
                    'data' => $monthArray,
                ]);
        } 
        return view('pages.teacher.dashboard.statistik-student',compact(
            'classroom',
            'lesson',
            'student',
            'materialwork',
        ));
    }

    public function previewWork(Classroom $classroom,lesson $lesson,Work $work)
    {
        return view('pages.teacher.dashboard.preview',compact(
            'classroom',
            'lesson',
            'work'
        ));
    }

    public function exportWorkStudent(Classroom $classroom ,lesson $lesson,Student $student)
    { 
        return Excel::download(new statistikExport($student,$classroom), 'statistikExport.xlsx');
    }


    public function classroomTest()
    {
        $lessonTeacher = LessonTeacher::with('classroom')
        ->where('teacher_id',auth()->user()->teacher_id)
        ->groupBy('classroom_id')
        ->get();

        return view('pages.teacher.dashboard.test.classroom',compact(
            'lessonTeacher'
        ));
    }

    public function lessonTest(Classroom $classroom)
    {
        $lessons= lesson::with(['teachers'])->whereHas('teachers',function($q) use ($classroom){

            $q->where('teacher_id',auth()->user()->teacher_id)->where('classroom_id',$classroom->id);
            
        })->get();

        $workCountLesson  = Classroom::with(['works'])->where('id',$classroom->id)->first();

        return view('pages.teacher.dashboard.test.lesson',compact(
            'classroom',
            'lessons',
            'workCountLesson'
        ));
    }

    public function studentTest(Classroom $classroom ,lesson $lesson)
    {
        return view('pages.teacher.dashboard.test.student',compact(
            'classroom',
            'lesson'
        ));
    }

    public function statistiTest(Request $request,Classroom $classroom ,lesson $lesson,Student $student)
    {   
        
        $from = date($request->from_date);
        $to = date($request->to_date);
 
        
        $classroomSearch = Classroom::with(['tests'])->where('id',$classroom->id)->first();
        
        if($from && $to || $from){
            $classroomSearch = Classroom::with(['tests' => function ($q) use($from,$to) {
                $q->whereBetween('tests.created_at',[$from." 00:00:00",$to." 23:59:59"])
                    ->orWhereBetween('tests.created_at',[$from." 00:00:00",$from." 23:59:59"]);
            }])                    
            ->where('id',$classroom->id)->first();
        
        }
        
        if($request->ajax()){
            $statistikTest = Test::with(['classrooms','results' => function($q) use ($student) {

                $q->where('student_id',$student->id);
    
            }])->whereHas('classrooms',function($q) use ($classroom) {
                
                $q->where('classroom_id',$classroom->id);
                
            })
            ->whereYear('created_at', '=', $request->searchByYear ?  $request->searchByYear : date('Y'))
            ->get()
            ->groupBy(function($date){
               
                return \Carbon\Carbon::parse($date->created_at)->format('m');
            })
            ;
            
            $scoreTest = array();
            $score = 0;
            foreach($statistikTest as $key => $statistik){
                foreach($statistik as $st){
                    foreach($st->results as $result){
                       $score += (int) $result->score;
                    }
                }
                $score = $score / $statistik->count();
               

                $scoreTest[(int) $key - 1] = $score;
            }
            
            $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agus','Sep','Okt','Nov','Des'];
            
            for($i = 0;$i < count($months);$i++){
                if(!empty($scoreTest[$i])){
                    $monthArray[$i] = ['month' => $months[$i] , 'score' => $scoreTest[$i]];
                }else{
                    $monthArray[$i] = ['month' => $months[$i] , 'score' => 0];
                }
            }

            return response()->json([
                'data' => $monthArray,
            ]);

        }
        
        return view('pages.teacher.dashboard.test.statistik-student',compact(
            'classroom',
            'lesson',
            'student',
            'classroomSearch',
        ));
    }

}
