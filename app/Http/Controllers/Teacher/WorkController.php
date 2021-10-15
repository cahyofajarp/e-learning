<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\AllStudentRankingExport;
use App\Http\Controllers\Controller;
use App\Models\Answerwork;
use App\Models\Classroom;
use App\Models\Filework;
use App\Models\lesson;
use App\Models\LessonTeacher;
use App\Models\Materialwork;
use App\Models\Student;
use App\Models\Work;
use App\Notifications\WorkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class WorkController extends Controller
{
    public function classroom()
    {
        $lessonTeacher = LessonTeacher::with('classroom')
                    ->where('teacher_id',auth()->user()->teacher_id)
                    ->groupBy('classroom_id')
                    ->get();

        return view('pages.teacher.work.classroom',compact(
            'lessonTeacher'
        ));
    }
    public function lesson(Classroom $classroom)
    {

        $lessons= lesson::with(['teachers'])->whereHas('teachers',function($q) use ($classroom){

                $q->where('teacher_id',auth()->user()->teacher_id)->where('classroom_id',$classroom->id);
                
        })->get();

        $workCountLesson  = Classroom::with(['works'])->where('id',$classroom->id)->first();
        
        // dd($workCountLesson);
        return view('pages.teacher.work.lesson',compact(
            'classroom',
            'lessons',
            'workCountLesson'
        ));
    }

    public function checkCode(Request $request,Classroom $classroom,lesson $lesson)
    {
        $this->validate($request,[
            'code' => 'required',
        ]);
        
        if($lesson->code  == $request->code){

            // Session::put('code',$lesson->code);

            return response()->json(['success' => true]);
        }
        else{
            return response()->json(['success' => false]);
        }   
    }

    public function work(Classroom $classroom, lesson $lesson)
    {
        $works = Work::with(['fileworks'])->where('lesson_id',$lesson->id)->get();

        return view('pages.teacher.work.work',compact(
            'classroom',
            'works',
            'lesson'
        ));
    }

    public function preview(Classroom $classroom,lesson $lesson,Work $work)
    {
        return view('pages.teacher.work.preview',compact(
            'classroom',
            'lesson',
            'work'
        ));
    }

    public function create(Classroom $classroom,lesson $lesson)
    {
        
       

        $lessonTeacher = lesson::with(['teachers'])->where('id',$lesson->id)->first(); 
        
        
        return view('pages.teacher.work.create',compact(
            'classroom',
            'lesson',
            'lessonTeacher'
        ));
    }
    public function store(Request $request,Classroom $classroom,lesson $lesson)
    {

        if ($request->type == 'TUGAS') {
            $this->validate($request,[
                'description' => 'required',
                'type' => 'required|in:TUGAS,MATERI',
                'title' => 'required',
                'standard' => 'required',
                'due' => 'required|date',
                'file.*' => 'required|mimes:pdf|max:10000'
            ]);
    
    
            $data = [
                'teacher_id' => auth()->user()->teacher_id,
                'lesson_id' => $lesson->id,
                'description' => $request->description,
                'type' => $request->type,
                'standard' => $request->standard,
                'title' => $request->title,
                'due' => $request->due
            ];
    
            $work = Work::create($data);
    
            $work->classrooms()->sync([$classroom->id]);
    
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $files = $file->store('file_work', 'public');
    
                    Filework::create([
                        'work_id' => $work->id,
                        'file' => $files
                    ]);
                }
            }
            
            $user = LessonTeacher::with(['classroom'])->where('classroom_id',$classroom->id)->first();        
           
            foreach($user->classroom->students as $student){
                    $student->user->notify(new WorkNotification($lesson,$work));
            }
        }else{

            $this->validate($request,[
                'description' => 'required',
                'type' => 'required|in:TUGAS,MATERI',
                'title' => 'required',
                'file.*' => 'required|mimes:pdf|max:10000'
            ]);

             $data = [
                'teacher_id' => auth()->user()->teacher_id,
                'lesson_id' => $lesson->id,
                'description' => $request->description,
                'type' => $request->type,
                'standard' => '-',
                'title' => $request->title,
                'due' => '-'
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
            
        }

        // $user = LessonTeacher::with(['classroom'])->where('classroom_id',$classroom->id)->first();        
       
        // foreach($user->classroom->students as $student){
        //         $student->user->notify(new WorkNotification($student->user));
        // }
              
        
    }

    public function edit(Classroom $classroom,lesson $lesson,Work $work)
    {

        
        $lessonTeacher = lesson::with(['teachers'])->where('id',$lesson->id)->first(); 


        return view('pages.teacher.work.edit',compact(
            'classroom',
            'lesson',
            'work',
            'lessonTeacher'
        ));
    }   
    
    public function update(Request $request,Classroom $classroom,lesson $lesson,Work $work)
    {
        
        $this->validate($request,[
            'description' => 'required',
            'title' => 'required',
            'standard' => 'required',
            'type' => 'required|in:TUGAS,MATERI',
            'due' => 'required|date',
            'file.*' => 'required|mimes:pdf|max:10000'
        ]);


        $files = Filework::where('work_id',$work->id);
        
        $work->update([
            'title' => $request->title,
            'type' => $request->type,
            'standard' => $request->standard,
            'description' => $request->description,
            'due' => $request->due,
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
                    'file' => $files,
                ];
    
                Filework::create($data);
    
    
            }
        }
        else{
            
            
            if($request->hasFile('file')){
                
                $fileStorage = $request->file('file')->store('file_work', 'public');
                
                $data = [
                    'work_id' => $work->id,
                    'file' => $fileStorage
                ];
    
                Filework::create($data);

            }else{
                
                foreach($work->fileworks as $filework){
                    Storage::disk('local')->delete('public/'.$filework->file);
                }

                $files->delete();

            }
        }
        

        return response()->json(['success' => true]);
        
    }

    public function score(Classroom $classroom,lesson $lesson,Work $work)
    {
        
        $materialWork = Materialwork::with(['students','work','answerwork'])->where('material_file','<>','-')->where('work_id',$work->id)->get(); 
        
        $studentWorkDone = 0;
        $studentWorkNotYet = 0;

        $studentWorkNotYet = $classroom->students()->count();

        foreach ($classroom->students as $student) {
            $studentWorkDone += $student->materialworks()->where('material_file','<>','-')->where('work_id',$work->id)->get()->count();
        }   
        
        $studentWorkNotYet -= $studentWorkDone;

        
        $studentWorkDataNotYet = array();

        if($classroom->students()->count() == $studentWorkDone){
            $studentWorkDataNotYet = [];
        }
        
        else if($materialWork->count() > 0){
            foreach($materialWork  as $material){
                foreach($material->students as $studentWork){
                    foreach($classroom->students as $student){
                        if($studentWork->id != $student->id){
                            $studentWorkDataNotYet[] = $student;
                        }
                    }
                }
            }
        }
        else{
            $studentWorkDataNotYet = $classroom->students()->get();    
        }

        return view('pages.teacher.work.score',compact(
            'classroom',
            'lesson',
            'work',
            'materialWork',
            'studentWorkDone',
            'studentWorkNotYet',
            'studentWorkDataNotYet'
        ));
    }

    public function giveScore(Classroom $classroom,lesson $lesson,Work $work,Student $student)
    {   

        if($student->materialworks->first()){
            if($answerWork = Answerwork::where('materialwork_id',$student->materialworks->first()->id)->first())
            {
                return view('pages.teacher.work.giveScore',compact(
                    'classroom',
                    'lesson',
                    'work',
                    'student',
                    'answerWork'
                ));
            }
        }
       
        else{
            return redirect()->back()->with('error', 'Tidak bisa masuk ke halaman karna siswa belum mengerjakan dan waktu belum selesai'); 
        }

        
     
    }

    public function workFileDownload(Classroom $classroom,lesson $lesson,Work $work,Student $student)
    {
        $file_path = public_path($student->materialworks()->where('work_id',$work->id)->first()->material_file);

        return response()->download( $file_path);
    }

    public function scoreCreate(Request $request,Classroom $classroom,lesson $lesson,Work $work,Student $student)
    {   
        $this->validate($request,[
            'score' => 'required|numeric',
    
        ]);
        
        $materialwork = $student->materialworks->where('work_id',$work->id)->first();
        if($materialwork->answerwork == null){
            Answerwork::create([
                'slug' => Str::random(10),
                'score' => $request->score,
                'materialwork_id' => $materialwork->id
            ]);

        }
        else{
            $materialwork->answerwork->update(['score' => $request->score]);
        }
       

        return response()->json(['success' => true]);
    }
    
    public function ranking(Classroom $classroom,lesson $lesson,Work $work)
    {   

       
        $answerworks = Answerwork::whereIn('materialwork_id',$work->materialworks()->pluck('id'))->get();
        
        $studentData = array();
        foreach($answerworks->sortByDesc('score') as $key => $answerwork){
            foreach($answerwork->materialwork->students as $student){
                $studentData[] = [
                    'name' => $student->name,
                    'score' => $answerwork->score,
                    'standard' => $work->standard,
                    'file' => $answerwork->materialwork->material_file
                ];
                
            }
            
        }

        return view('pages.teacher.work.ranking',compact(
            'classroom',
            'lesson',
            'work',
            'studentData'
        ));
    }

    public function exportAllResultStudentWork(Classroom $classroom,lesson $lesson,Work $work)
    {
        $answerworks = Answerwork::whereIn('materialwork_id',$work->materialworks()->pluck('id'))->get();
        
        $studentData = array();
        foreach($answerworks->sortByDesc('score') as $key => $answerwork){
            foreach($answerwork->materialwork->students as $student){
                $studentData[] = [
                    'name' => $student->name,
                    'score' => $answerwork->score,
                    'standard' => $work->standard,
                    'file' => $answerwork->materialwork->material_file
                ];
                
            }
        }
        
        return Excel::download(new AllStudentRankingExport, 'exportAllResultStudentWork.xlsx');
        
        // return (new AllStudentRankingExport)->download('invoices.xlsx');
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
