<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Filework;
use App\Models\lesson;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class StudentWork extends Controller
{
    public function classroom()
    {
        $classrooms = Classroom::all();
        return view('pages.admin.student.studentWork.classroom',compact(
            'classrooms'
        ));
    }

    public function lesson(Classroom $classroom)
    {
        
        $lessons = lesson::where('classroom_id',$classroom->id)->get();
        return view('pages.admin.student.studentWork.lesson',compact(
            'classroom',
            'lessons'
        ));
    }

    public function index(Classroom $classroom,lesson $lesson)
    {

        $works = Work::with(['fileworks'])->where('lesson_id',$lesson->id)->get();

        return view('pages.admin.student.studentWork.index',compact(
            'classroom',
            'lesson',
            'works'
        ));
    }

    public function checkCode(Request $request,Classroom $classroom,lesson $lesson)
    {
        $this->validate($request,[
            'code' => 'required'
        ]);

        if($request->code == $lesson->code){
            Session::put('code',$lesson->code);
            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false]);
        }

    }

    public function create(Classroom $classroom,lesson $lesson)
    {

        $lessonTeacher = lesson::with(['teachers'])->where('id',$lesson->id)->first(); 

        return view('pages.admin.student.studentWork.create',compact(
            'classroom',
            'lesson',
            'lessonTeacher'
        ));
    }
    
    public function store(Request $request,Classroom $classroom,lesson $lesson)
    {

        $this->validate($request,[
            'teacher_id' => 'required|exists:teachers,id',
            'description' => 'required',
            'title' => 'required',
            'due' => 'required|date',
            'file.*' => 'required|mimes:pdf|max:10000'
        ]);


        $data = [
            'teacher_id' => (int) $request->teacher_id,
            'lesson_id' => $lesson->id,
            'description' => $request->description,
            'title' => $request->title,
            'due' => $request->due
        ];

        $work = Work::create($data);

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $files = $file->store('file_work', 'public');

                Filework::create([
                    'work_id' => $work->id,
                    'file' => $files
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function preview(Classroom $classroom,lesson $lesson,Work $work)
    {
        return view('pages.admin.student.studentWork.preview',compact(
            'classroom',
            'lesson',
            'work'
        ));
    }

    public function edit(Classroom $classroom,lesson $lesson,Work $work)
    {

        
        $lessonTeacher = lesson::with(['teachers'])->where('id',$lesson->id)->first(); 


        return view('pages.admin.student.studentWork.edit',compact(
            'classroom',
            'lesson',
            'work',
            'lessonTeacher'
        ));
    }   
    
    public function update(Request $request,Classroom $classroom,lesson $lesson,Work $work)
    {
        
        $this->validate($request,[
            'teacher_id' => 'required|exists:teachers,id',
            'description' => 'required',
            'title' => 'required',
            'due' => 'required|date',
            'file.*' => 'required|mimes:pdf|max:10000'
        ]);


        $files = Filework::where('work_id',$work->id);
        
        $work->update([
            'title' => $request->title,
            'due' => $request->due,
            'teacher_id' => $request->teacher_id
        ]);
        
        if($request->file_id){

            $checkFile = Filework::where('work_id',$work->id)->whereNotIn('id',$request->file_id);
            
            if($checkFile->get()->count() > 0){

                foreach($checkFile->get() as $filework){
                    Storage::disk('local')->delete('public/'.$filework->file);
                }
    
                $checkFile->get()->each(function($msg){
                    $msg->delete();
                });
            }
    
            if($request->hasFile('file')){
                
                $files = $request->file('file')->store('file_work', 'public');
                
                $data = [
                    'work_id' => $work->id,
                    'file' => $files
                ];
    
                Filework::create($data);
    
    
            }
        }
        else{
            
            
            if($request->hasFile('file')){
                
                $files = $request->file('file')->store('file_work', 'public');
                
                $data = [
                    'work_id' => $work->id,
                    'file' => $files
                ];
    
                Filework::create($data);

            }else{
                
                Storage::disk('local')->delete('public/'.$work->fileworks->pluck('file')->first());
                $files->delete();

            }
        }
        

        return response()->json(['success' => true]);
        
    }



    public function destroy(Classroom $classroom,lesson $lesson,Work $work)
    {

        foreach($work->fileworks as $filework){
            Storage::disk('local')->delete('public/'.$filework->file);
        }
        
        $work->delete();

        return response()->json(['success' => true]);
    }
}
