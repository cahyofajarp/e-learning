<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MailCreateTest;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\Levelclass;
use App\Models\Teacher;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TestController extends Controller
{
    

    public function index()
    {
        
        $classrooms = Classroom::with('levelclass')->get();

        return view('pages.admin.test.index',compact(
            'classrooms'
        ));
    }

    public function test(Classroom $classroom)
    {   
        session()->forget('code');
        $lessons = lesson::where('classroom_id',$classroom->id)->get();

        return view('pages.admin.test.test',compact(
            'classroom',
            'lessons'
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

    public function testCreate(Classroom $classroom,lesson $lesson)
    {   


        $user = User::where('roles','admin')->where('id',auth()->user()->id)->first();

        $tests = Test::where('teacher_id',auth()->user()->teacher_id)
                        ->orWhere('created_by',$user->roles)
                        ->orWhere('created_by','=','guru')
                        ->where('lesson_id',$lesson->id)
                        ->get();

        if(!Session::get('code')){
            return redirect()->back();
        }else{
            return view('pages.admin.test.testCreate',compact(
                'classroom',
                'lesson',
                'tests',
            ));
        }
        
    }

    public function testCreateView(Classroom $classroom,lesson $lesson)
    {
        $lessons = lesson::with(['teachers'])->where('id',$lesson->id)->first();

        $levelclasses = Levelclass::with(['classrooms' => function($q) use ($classroom){
        
                return $q->where('name','like','%'.substr($classroom->name,0,-1).'%');
        
            }])->where('id',$classroom->levelclass->id)->get();

        return view('pages.admin.test.testCreateView',compact(
            'classroom',
            'lesson',
            'lessons',
            'levelclasses'
        ));
    }

    public function store(Request $request,Classroom $classroom,lesson $lesson)
    {
        $this->validate($request,[
            'name' => 'required',
            'test_code' => 'required',
            'time' => 'required|numeric',
            'standard' => 'required|numeric',
            'lesson_id' => 'required|exists:lessons,id',
            // 'start_test' => 'required|date|after:'.Carbon::now(),
            // 'deadline_test' => 'required|date|after:start_test|before_or_equal:'.Carbon::parse($request->start_test)->addDay(1),
            'decription' => 'required',
            'teacher_id' => 'required|exists:teachers,id'
        ]);


        $data = $request->except(['add_class_test','start_test','deadline_test']);

        $data['slug'] = Str::random(10);
        $data['status'] = 0;
        $data['created_by'] = 'admin';

        $test = Test::create($data);

        $teacher = User::latest()
                        ->where('roles','admin')
                        ->where('id',auth()->user()->id)
                        ->orWhere('teacher_id',$test->teacher_id)
                        ->get();
        
        
        foreach ($teacher as $key => $item) {
            Mail::to($item->email)->send(
                new MailCreateTest($test)
            );
        }
        
        if($request->add_class_test){

            $stack = array($classroom->id);
            array_push($stack, (int)$request->add_class_test[0]);

            $test->classrooms()->sync($stack);

        }
        else{
            $test->classrooms()->sync($classroom->id);
        }

        

        return response()->json(['success' => true]);
    }

    public function classCheck(Classroom $classroom,lesson $lesson,Test $test)
    {
        $testClass = Test::with(['classrooms'])->where('id',$test->id)->first();
        
        return response()->json(['test' => $testClass]);
    }

    public function testMiddleware(Classroom $classroom,lesson $lesson,$localStorageData)
    {
        if($localStorageData == "null"){                                                     
            // return redirect()->route('admin.test.testCreate',[$classroom,$lesson]);
            return response()->json(['data' => false]);
        }
        else{
            return response()->json(['data' => true]);
        }
    }

    public function destroy(Classroom $classroom,lesson $lesson,Test $test)
    {
        $test->delete();

        if($test){
            return response()->json(['success' => true]);
        }
    }
}
