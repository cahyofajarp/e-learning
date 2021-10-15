<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\LessonTeacher;
use App\Models\Levelclass;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function classroom()
    {
        $lessonTeacher = LessonTeacher::with('classroom')
                    ->where('teacher_id',auth()->user()->teacher_id)
                    ->groupBy('classroom_id')
                    ->get();

        return view('pages.teacher.test.classroom',compact(
            'lessonTeacher'
        ));
    }

    public function lesson(Classroom $classroom)
    {

        $lessons= lesson::with(['teachers'])->whereHas('teachers',function($q) use ($classroom){

                $q->where('teacher_id',auth()->user()->teacher_id)->where('classroom_id',$classroom->id);
                
        })->get();

        $testCountLesson = Classroom::with(['tests' => function($q) use ($classroom){

            $q->where('classroom_id',$classroom->id);

        }])->where('id',$classroom->id)
        ->first();


        return view('pages.teacher.test.lesson',compact(
            'classroom',
            'lessons',
            'testCountLesson'
        ));

    }

    public function checkCode(Request $request,Classroom $classroom,lesson $lesson)
    {
        $this->validate($request,[
            'code' => 'required',
        ]);
        
        if($lesson->code  == $request->code){

            Session::put('code',$lesson->code);

            return response()->json(['success' => true]);
        }
        else{
            return response()->json(['success' => false]);
        }   
    }

    public function indexTest(Classroom $classroom,lesson $lesson)
    {   
        
        $classtests = Classroom::with(['tests' => function($q) use ($classroom,$lesson){

            $q->where('classroom_id',$classroom->id)->where('lesson_id',$lesson->id);

        }])->where('id',$classroom->id)
        ->first();

        if(!Session::get('code')){

            return redirect()->back();

        }else{

            return view('pages.teacher.test.index',compact(
                'classroom',
                'lesson',
                'classtests',
            ));

            

        }
    }
    public function create(Classroom $classroom,lesson $lesson)
    {
        $lessons = lesson::with(['teachers'])->where('id',$lesson->id)->first();

        $levelclasses = Levelclass::with(['classrooms' => function($q) use ($classroom){
        
                return $q->where('name','like','%'.substr($classroom->name,0,-1).'%');
        
            }])->where('id',$classroom->levelclass->id)->get();

        
        $teacherLesson = LessonTeacher::with(['classroom'])
        ->whereHas('classroom', function($q) use ($classroom){
            
            return $q->where('levelclass_id',$classroom->levelclass->id)->where('name','like','%'.substr($classroom->name,0,-1).'%');
    
        })
        ->where('teacher_id',auth()->user()->teacher_id)->get();


        return view('pages.teacher.test.create',compact(
            'classroom',
            'lesson',
            'lessons',
            'levelclasses',
            'teacherLesson'
        ));
    }

    public function store(Request $request,Classroom $classroom,lesson $lesson)
    {
        $this->validate($request,[
            'name' => 'required',
            'test_code' => 'required',
            'time' => 'required|numeric',
            'standard' => 'required|numeric',
            'decription' => 'required',
        ]);

        $data = $request->except(['add_class_test','lesson_id']);

        $data['slug'] = Str::random(10);
        $data['status'] = 0;
        $data['teacher_id'] = auth()->user()->teacher_id;
        $data['created_by'] = 'guru';
        
        $test = Test::create($data);

        // $teacher = User::latest()
        //                 ->where('roles','admin')
        //                 ->where('id',auth()->user()->id)
        //                 ->orWhere('teacher_id',$test->teacher_id)
        // //                 ->get();
        
            
        // // foreach ($teacher as $key => $item) {
        // //     Mail::to($item->email)->send(
        // //         new MailCreateTest($test)
        // //     );
        // // }
        
        if($request->add_class_test){

            DB::table('classroom_test')->insert([
                'classroom_id' => $classroom->id,
                'test_id' => $test->id,
                'lesson_id' => $lesson->id,
                'created_at' =>  \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(), 
            ]);

            foreach($request->add_class_test as $key => $val){
               
                $lessonId = lesson::whereIn('classroom_id',[$val])->where('name',$lesson->name)->get();

                foreach($lessonId as $IDLesson){
                    
                    DB::table('classroom_test')->insert([
                        'classroom_id' => $IDLesson->classroom_id,
                        'test_id' => $test->id,
                        'lesson_id' => $IDLesson->id,
                        'created_at' =>  \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),  
                    ]);
                } 
               
            }
        }
        else{

            $test->classrooms()->sync($classroom->id);
            
            DB::table('classroom_test')->where('test_id',$test->id)->update(['lesson_id' => $lesson->id]);

            
        }


        return response()->json(['success' => true]);
    }
    
    public function edit(Classroom $classroom,lesson $lesson,Test $test)
    { 
        
    $levelclasses = Levelclass::with(['classrooms' => function($q) use ($classroom){
        
        return $q->where('name','like','%'.substr($classroom->name,0,-1).'%');

    }])->where('id',$classroom->levelclass->id)->get();

        return view('pages.teacher.test.edit',compact(
            'classroom',
            'lesson',
            'test',
            'levelclasses'
        ));
    }
    
    public function update(Request $request,Classroom $classroom,lesson $lesson,Test $test)
    {
        $this->validate($request,[
            'name' => 'required',
            'test_code' => 'required',
            'time' => 'required|numeric',
            'standard' => 'required|numeric',
            'lesson_id' => 'required|exists:lessons,id',
            'decription' => 'required',
        ]);
        
        if($request->add_class_test){

            $stack = array($classroom->id);
            array_push($stack, (int)$request->add_class_test[0]);

            $test->classrooms()->sync($stack);

        }
        else{
            $test->classrooms()->sync([$classroom->id]);
            
        }

        $test->update([
            'name' => $request->name,
            'test_code' => $request->test_code,
            'time' => $request->time ,
            'standard' => $request->standard,
            'decription' => $request->decription,
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function checkClass(Classroom $classroom,lesson $lesson,Test $test)
    {
        $testClass = Test::with(['classrooms'])->where('id',$test->id)->first();
        
        return response()->json(['test' => $testClass]);
    }

    public function destroy(Classroom $classroom,lesson $lesson,Test $test)
    {
        
        $testDel = Test::with(['classrooms'])->whereHas('classrooms',function($q) use ($classroom,$lesson){
            
            $q->where('classroom_id',$classroom->id)->where('lesson_id',$lesson->id);
            
        })->where('id',$test->id)->get();

        $testCount = Classroom::with(['tests'])->whereHas('tests' ,function($q) use ($test){
            $q->where('test_id',$test->id);

        })->count();

        
        $data= array();

        foreach($testDel as $val){
            foreach ($val->classrooms as $classroomVal) {
                if($classroomVal->id != $classroom->id){
                    $data[] = $classroomVal->id;
                }
            }  
        }

        
        $test->classrooms()->sync($data); // sinkronisai data yang tidak ada do request hehe
        
        if($testCount == 1){ // --> 
            $test->delete();
        }
        
        if($test){
            return response()->json(['success' => true]);
        }
    }
}


// membagongkan sekali ini susah amat buat gnian doang