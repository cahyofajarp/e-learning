<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\Levelclass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with('levelclass')->get();
        
        return view('pages.admin.lesson.index',compact(
            'classrooms',
        ));
    }

    public function classroomLesson(Classroom $classroom)
    {
        $levelclasses = Levelclass::with('classrooms')->where('id',$classroom->levelclass->id)->first();
        
        $lessons = lesson::where('classroom_id',$classroom->id)->get();

        return view('pages.admin.lesson.lesson',compact(
            'classroom',
            'levelclasses',
            'lessons'
        ));
    }

    public function store(Request $request,Classroom $classroom)
    {
        $this->validate($request,[
            'code' => 'required|max:10|unique:lessons,code,NULL,id,classroom_id,'.$classroom->id,
            'name' => 'required|unique:lessons,name,NULL,id,classroom_id,'.$classroom->id
         ]);


        $data = $request->all();
        $data['slug'] = Str::random(10);
        $data['classroom_id'] = $classroom->id;
         $lesson = lesson::create($data);

         return response()->json(['success' => true]);
    }

    public function getRandomCode()
    {
        return response()->json(['code' => Str::random(10)]);
    }

    public function edit(Classroom $classroom,lesson $lesson)
    {
        return response()->json([
            'lesson' => $lesson
        ]);
    }

    public function update(Request $request,Classroom $classroom,lesson $lesson)
    {
        $this->validate($request,[
            'code' => 'required|max:10|unique:lessons,code,'.$lesson->id.',id,classroom_id,'.$classroom->id,
            'name' => 'required|unique:lessons,name,'.$lesson->id.',id,classroom_id,'.$classroom->id
         ]);
        
         $lesson->update([
             'name' => $request->name,
             'code' => $request->code,
             'type' => $request->type
         ]);
        
         return response()->json(['success' => true]);
    }

    public function addLessonAutomatic(Request $request,Classroom $classroom)
    {


        $lessons  = lesson::where('classroom_id',$classroom->id)->get();
        
        $true = false;
        
        $checkLesson = lesson::whereIn('classroom_id',$request->add_lesson)->get();

        foreach($request->add_lesson  as $no => $classroom_id){
           

            foreach ($lessons as $lesson) {
               if($checkLesson->count() > 0){
                    foreach ($checkLesson as $value) {
                    
                       if($value->where('classroom_id',$value->classroom_id)
                       ->where('name',$lesson->name)->count('name') == 1){
                           $true = false;
                        }
                        elseif($value->where('classroom_id',$value->classroom_id)
                        ->where('name',$lesson->name)->count('name') == 0){

                            $data = array(
                                'classroom_id' => $classroom_id,
                                'type' => $lesson->type,
                                'slug' => Str::random(10),
                                'code' => Str::random(10),
                                'name' => $lesson->name
                            );

                            $true = true;
                            lesson::create($data);
                            
                        }
                    }
               }
               else{
                    $data = array(
                        'classroom_id' => $classroom_id,
                        'type' => $lesson->type,
                        'slug' => Str::random(10),
                        'code' => Str::random(10),
                        'name' => $lesson->name
                    );

                    $true = true;
                    lesson::create($data);

               }
            }
        }

        if($true == false){
                
            // echo 'Oops, Mapel di kelas '. str_replace(array('[',']','"'),'',$value->classroom()->where('id',$classroom_id)->pluck('name')) .' di tambah kan semua';
            // Session::flash('message', 'Oops, Mapel di kelas '. str_replace(array('[',']','"'),'',$value->classroom()->where('id',$classroom_id)->pluck('name')) .' di tambah kan semua');
            Session::flash('message','Oops,Kelas yang anda pilih sudah ada pelajaran nya semua!');
            return Redirect::back();    

        }
        else if($true == true){
            Session::flash('success', 'Yeay, Sukses Menambahkan Pelajaran!');
            return Redirect::back();
        }

    }

    public function destroy(Classroom $classroom,lesson $lesson)
    {
        $lesson->delete();
        return response()->json(['success' => true]);
    }
}
