<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\LessonTeacher;
use App\Models\Materialwork;
use App\Models\Result;
use App\Models\Student;
use App\Models\Test;
use App\Models\Work;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function all()
    {
        $classroom = Classroom::where('id',auth()->user()->student->classroom->id)->first();

        $lessons = LessonTeacher::with(['lesson','teacher'])->where('classroom_id',$classroom->id)->get();

        return view('pages.student.dashboard.statistik',compact(
            'lessons',
            'classroom'
        ));
    }

    public function lesson(lesson $lesson)
    {
        $tests = Test::with(['classrooms','results' => function($q){
            $q->where('student_id',auth()->user()->student_id);
        }])->whereHas('classrooms',function($q) use ($lesson) {
            $q->where('lesson_id',$lesson->id);
        })->get();


        $student = Student::with(['materialworks','materialworks.work' => function($q)  use($lesson)  {
            $q->where('lesson_id',$lesson->id);
        },'materialworks.answerwork'])
                
                ->where('id',auth()->user()->student_id)->first();
        
        $works = Work::where('lesson_id',$lesson->id)->get();
        


        return view('pages.student.dashboard.statistik-lesson',compact(
            'lesson',
            'tests',
            'student',
            'works',
            
        ));
    }
}
