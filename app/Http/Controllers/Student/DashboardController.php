<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\Result;
use App\Models\Student;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function notification($id)
    {
        $user = User::where('id',$id)->first();
        
        return response()->json(['data' => $user->notifications]);
    }

    public function dashboard()
    {   
     
        $classroom = Student::where('id',auth()->user()->student->id)->first();

        $lesson = lesson::with(['teachers'])->where('classroom_id',$classroom->classroom->id)->get();

        $test = Classroom::withCount(['tests' => function($q) use ($classroom){
            $q->where('classroom_id',$classroom->classroom->id)
                ->whereNotNull('start_test')
                ->whereNotNull('deadline_test'); 

        }])->where('id',$classroom->classroom->id)->first();
        
        $work = Classroom::withCount(['works' => function($q) use ($classroom){
            $q->where('classroom_id',$classroom->classroom->id)
                ->where('due','>',\Carbon\Carbon::now())
                ->where('type','TUGAS');

        }])->where('id',$classroom->classroom->id)->first();


        $newWork = Classroom::with(['works' => function($q){
            $q->where('type','TUGAS')
            ->where('due','>',\Carbon\Carbon::now());
        },'works.materialworks'])->where('id',$classroom->classroom->id)->first();
        
        $dataWork = Work::with(['materialworks','materialworks.students'])->where('due','>',\Carbon\Carbon::now())->get();
        
        $dataCount = 0;
        foreach($dataWork  as $workData){
            foreach ($workData->materialworks as $material) {
                $dataCount += $material->students()->where('student_id',auth()->user()->student_id)->get()->count();
            }
        }
        
        $clr = Classroom::with(['tests' => function($q) {
            
            return $q->where('deadline_test','>',\Carbon\Carbon::now());

        }])->where('id',$classroom->classroom->id)->first();

        $countTest = 0;

        foreach($clr->tests as $test){
            $result = Result::where('test_id',$test->id)->where('student_id',auth()->user()->student_id)->get()->count();
            $countTest += $result;
        }

        $countTest = $clr->tests->count() - $countTest;

        return view('pages.student.dashboard.dashboard',compact(
            'classroom',
            'lesson',
            'test',
            'work',
            'dataCount',
            'countTest'
        ));
    }
}
