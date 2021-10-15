<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\LessonTeacher;
use App\Models\Question;
use App\Models\Result;
use App\Models\Student;
use App\Models\Test;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    

    public function lesson()
    {
        $lessons = lesson::where('classroom_id',auth()->user()->student->classroom->id)->get();

        $classroom = Classroom::with(['tests' => function($q){
            // $q->where('deadline_test','>',\Carbon\Carbon::now());
        }])->where('id',auth()->user()->student->classroom->id)->first();

       $results = Result::where('student_id',auth()->user()->student->id)->get();

        

        return view('pages.student.test.lesson',compact(
            'lessons',
            'classroom',
            'results'
        ));
    }

    public function index(lesson $lesson)
    {
      $classroom = Classroom::with(['tests' => function($q) use ($lesson){
            $q->where('lesson_id',$lesson->id);
        }])->where('id',auth()->user()->student->classroom->id)->first();
        
       $result = Result::where('student_id',auth()->user()->student->id)->get();

        return view('pages.student.test.index',compact(
            'lesson',
            'classroom',
            'result'
        ));
    }   

    public function startTest(lesson $lesson,Classroom $classroom,Test $test)
    {

        
       $result = Result::where('student_id',auth()->user()->student->id)->where('test_id',$test->id)->first();

      
       if($result){
        if($result->score){
            return redirect()->route('student.test.index',$lesson);
       }
    }

        return view('pages.student.test.start_test',compact(
            'lesson',
            'test',
            'classroom',
            'result'
        ));
    }

    public function checkCode(Request $request,lesson $lesson,Classroom $classroom,Test $test)
    {
        $this->validate($request,[
            'code' => 'required',
        ]);
        
        if($test->test_code  == $request->code){

            // Session::put('code',$lesson->code);

            return response()->json(['success' => true]);
        }
        else{
            return response()->json(['success' => false]);
        }   
    }

    public function start(lesson $lesson,Classroom $classroom,Test $test)
    {

        $randomId = Question::where('test_id',$test->id)->inRandomOrder()->pluck('id');
        Session::put('randomId',$randomId); 

        $rq = Session::get('randomId')->implode(',');
        $questions = Question::whereIn('id',Session::get('randomId'))->orderByraw("FIELD(id,$rq)")->get();

        $startTime =$startTime=\Carbon\Carbon::now()->addMinutes($test->time);
        
        $result = Result::where('student_id',auth()->user()->student->id)->where('test_id',$test->id)->first();
        
        if(empty($result)){
            $result = Result::create(['slug' => Str::random(10),'test_id' => $test->id,'student_id' => auth()->user()->student->id,'start_test' => $startTime]);
            
        }

        if($result){
            if($result->score != NULL){
                return redirect()->route('student.test.index',$lesson);
            }
        }
            
        return view('pages.student.test.start',compact(
            'lesson',
            'test',
            'classroom',
            'questions',
            'result',
            
        ));
    }

    public function store(Request $request,lesson $lesson,Classroom $classroom,Test $test,Result $result)
    {

        // Session::forget('startTest');

        $data = $request->except('answer','score');

        $count = Question::where('test_id',$test->id)->count();
        $answers = count($request->correct);

        $true = 0;
        $false = 0;
        $score = 0;
        
        $collect = collect($request->correct);

        $filtered = $collect->filter(function ($value,$key) {
            return $value != 'NULL';
        });
        $isAnswer = count($filtered);

        $notAnswer = $count - $isAnswer;

        

        for ($i=0; $i < $count; $i++) { 
            
            if($request->answer[$i] == $request->correct[$i]){
                $true++;
                $score += $request->score[$i];
            }

            else{
                $false++;
            }
            
            //-------------------------------------
            $data['slug'] = Str::random(10);
            $data['test_id'] =(int)  $test->id;
            $data['student_id'] = auth()->user()->student->id;
            $data['question_id'] = (int) $request->question_id[$i];
            $data['correct'] =  $request->correct[$i];

            Answer::create($data);

        }

        $standard = '';

        if($score > $test->standard){
            $standard = 'Lulus';
        }
        else{
            $standard = 'Tidak Lulus';
        }

        $result->update([
            'jml_soal' => $count,
            'terjawab' => $isAnswer,
            'tidak_terjawab' => $notAnswer,
            'jml_benar' => $true,
            'jml_salah' => $false,
            'score' => round($score),
            'status' => $standard,
        ]);
        

        return redirect()->route('student.test.startTest.result',[$lesson,$classroom,$test,$result]);
    }

    public function result(lesson $lesson,Classroom $classroom,Test $test,Result $result)
    {
        session()->forget('randomId');
        session()->forget('start_time');

        // $result = Result::where('student_id',auth()->user()->student->id)->where('test_id',$test->id)->first();
        
        if($result){
            if($result->score == NULL){
                return redirect()->route('student.test.index',$lesson);
            }
        }

        
        return view('pages.student.test.result',compact(
            'lesson',
            'classroom',
            'test',
            'result'
        ));
    }

    public function preview(lesson $lesson,Classroom $classroom,Test $test,Result $result)
    {
        $questions = Question::where('test_id',$test->id)->get();
        $answered = Answer::with(['question'])->where('test_id',$test->id)->where('student_id',auth()->user()->student->id)->get();
        
    
        if($result){
            if($result->score == NULL){
                return redirect()->route('student.test.index',$lesson);
            }
        }
        
        return view('pages.student.test.preview',compact(
            'lesson',
            'classroom',
            'test',
            'result',
            'questions',
            'answered'
        ));
    }
    public function middlewareTest(lesson $lesson,Classroom $classroom,Test $test)
    {

        $result = Result::where('student_id',auth()->user()->student->id)->where('test_id',$test->id)->first();
        
        Session::put('start_time',$result->start_test);
        
        $start_time = Session::get('start_time');

        if(request()->ajax()){
                 if($result){
                     if($result->score != NULL){
                         return response()->json([
                                         'result' => $result,
                                         'success' => true,
                                         'start_time' => null,
                                     ]);
                     }
                     else{
                         return response()->json([
                             'result' => $result,
                             'success' => false,
                             'start_time' => $start_time,
                         ]);
                     }
                 }
            }
    }
    public function testEndTime()
    {
        $now = \Carbon\Carbon::now();

        $tests = Test::with(['classrooms','results','results.student'])->whereNotNull('start_test')->whereNotNull('deadline_test')->get();
        
        foreach($tests as $test){
            if($now > $test->deadline_test){
                        $classrooms = Classroom::with(['tests'])
                        ->whereHas('tests', function($q) use ($test){
                            $q->where('test_id',$test->id);
                        })
                        ->get();

                    $questions = Question::with(['test'])->where('test_id',$test->id)->get();
            
                    $students = Student::with(['classroom','classroom.tests'])->whereIn('classroom_id',$classrooms->pluck('id'))->get();
                    

                    foreach($students as $student){
                            // echo $student->results()->where('test_id',$test->id)->count();
                        if($student->results()->where('test_id',$test->id)->count() == 0){
                            foreach($questions as $question){
                               Answer::create([
                                    'slug' =>  Str::random(10),
                                    'test_id' => (int)  $question->test->id,
                                    'student_id' =>  $student->id,
                                    'question_id' =>  (int) $question->id,
                                    'correct' =>   'NULL',
                                ]);
                                
                            }

                            $countQuestion = Question::where('test_id',$test->id)->get()->count();
                            
                                $result = Result::create([
                                    'test_id' => $test->id,
                                    'student_id' => $student->id,
                                    'slug'=> Str::random(10),
                                    'jml_soal' => $countQuestion,
                                    'terjawab' => 0,
                                    'tidak_terjawab' => $countQuestion,
                                    'jml_benar' => 0,
                                    'jml_salah' => 0,
                                    'score' => 0,
                                    'status' => 'Tidak Lulus',
                                    'start_test' => null
                                ]);
                        }    
                    }  
            }
        }
    }
}

