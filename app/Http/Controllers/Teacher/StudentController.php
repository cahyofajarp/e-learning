<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\LessonTeacher;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $lessonTeacher = LessonTeacher::with('classroom')->groupBy('classroom_id')->where('teacher_id',auth()->user()->teacher_id)->get();

        return view('pages.teacher.student.index',compact(
            'lessonTeacher'
        ));
    }

    public function studentAll(Classroom $classroom)
    {

        $students = Student::where('classroom_id',$classroom->id)->get();

        return view('pages.teacher.student.studentAll',compact(
            'classroom',
            'students'
        ));
    }

    public function statistik(Classroom $classroom,Student $student)
    {
        
        return view('pages.teacher.student.statistik',compact(
            'classroom',
            'student'
        ));
    }
}
