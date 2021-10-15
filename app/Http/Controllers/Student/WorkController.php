<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\Materialwork;
use App\Models\Student;
use App\Models\Work;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class WorkController extends Controller
{

    public function lesson()
    {
        $lessons = lesson::with(['works','teachers'])->where('classroom_id',auth()->user()->student->classroom->id)->get();
        
        return view('pages.student.material.lesson',compact(
            'lessons'
        ));

    }
    public function index(lesson $lesson)
    {
           
        $material = Work::with(['classrooms' => function($q) {
            $q->where('classrooms.id',auth()->user()->student->classroom_id);
        }])->where('type','MATERI')->get();

        return view('pages.student.material.index',compact(
            'lesson',
            'material'
        ));
    }

    public function nilai(lesson $lesson,Work $work)
    {
        $student = Student::where('id',auth()->user()->student_id)->first();

        return view('pages.student.work.nilai',compact(
            'lesson',
            'work',
            'student'
        ));
    }
    
    public function preview(lesson $lesson,Work $work)
    {
        return view('pages.student.material.preview',compact(
            'lesson',
            'work'
        ));
    }

    


    public function lessonWork()
    {
        $lessons = lesson::with(['works','teachers'])->where('classroom_id',auth()->user()->student->classroom->id)->get();
        
        return view('pages.student.work.lesson',compact(
            'lessons'
        ));

    }

    public function indexWork(lesson $lesson)
    {
        $material = Classroom::with(['works' => function($q) use($lesson) {
            
            $q->where('type','TUGAS')->where('lesson_id',$lesson->id);
            
        },'works.materialworks'])->where('id',auth()->user()->student->classroom_id)->first();
        
        
        $student = Student::with(['materialworks'])->where('id',auth()->user()->student_id)->first(); 

        return view('pages.student.work.index',compact(
            'lesson',
            'material',
            'student'
        ));
    }

    public function previewWork(lesson $lesson,Work $work)
    {
        $Materialwork = Materialwork::with(['students'])->where('work_id',$work->id)->get();
        $student = Student::with(['materialworks'])->where('id',auth()->user()->student_id)->first(); 
        // $material_work = DB::table('materialwork_student')->where('student_id',auth()->user()->student_id)->first();
        
        return view('pages.student.work.preview',compact(
            'lesson',
            'work',
            'Materialwork',
            'student'
        ));
    }

    public function sendWork(Request $request,lesson $lesson,Work $work)
    {
        $this->validate($request,[
            'file_work' => 'required|mimes:pdf|max:10000'
        ]);
        if(\Carbon\Carbon::now() > $work->due){
            return response()->json(['success' => false]);
        }
        else{
            if($request->hasFile('file_work')){
               
                    $path = $request->file('file_work');            
                    $nama_file = rand().'_'.str_replace(' ','_',auth()->user()->student->name).'_'.$path->getClientOriginalName();
                    $path->move('tugas_'.\Carbon\Carbon::now()->isoFormat('YYYY').'/'.auth()->user()->student->classroom->levelclass->name.'/'.auth()->user()->student->classroom->name.'/'.$work->title.'/'.$lesson->name, $nama_file);
        
                    $pathDB = 'tugas_'.\Carbon\Carbon::now()->isoFormat('YYYY').'/'.auth()->user()->student->classroom->levelclass->name.'/'.auth()->user()->student->classroom->name.'/'.$work->title.'/'.$lesson->name;
        
        
                    $Materialwork = Materialwork::create([
                        'material_file' => $pathDB.'/'.$nama_file,
                        'slug' => Str::random(10),
                        'status' => 'SELESAI',
                        'work_id' => $work->id
                    ]);
        
            }
            
            $Materialwork->students()->sync(auth()->user()->student_id);

            return response()->json(['success' => true]);
        }


    }

}
