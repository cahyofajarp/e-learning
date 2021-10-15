<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\LessonTeacher;
use App\Models\Teacher;
use Illuminate\Http\Request;

class LessonTeacherController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with(['levelclass','lessons','lessons.teachers'])->get();
        $lessons = lesson::with(['teachers'])->get();
        
        $LessonTeacher = LessonTeacher::all();
        return view('pages.admin.lessonTeacher.index',compact(
            'classrooms',
            'lessons',
            'LessonTeacher'
        ));
    }

    public function create(Classroom $classroom)
    {
        
        $lessons = lesson::where('classroom_id',$classroom->id)->get();
        $teachers = Teacher::all();

        return view('pages.admin.lessonTeacher.create',compact(
            'classroom',
            'lessons',
            'teachers',
        ));
    }

    public function store(Request $request,Classroom $classroom,lesson $lesson)
    {
        $this->validate($request,[
            'teacher_id' => 'required'
        ]);


        $lesson->teachers()->sync($request->teacher_id);
        
        $LessonTeacher = LessonTeacher::where('lesson_id',$lesson->id)->first();

        $LessonTeacher->update([
            'classroom_id' => $classroom->id
        ]);
        
        return response()->json(['success' => true]);
    }
}
