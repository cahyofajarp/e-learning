<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Imports\QuestionImport;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\LessonTeacher;
use App\Models\Levelclass;
use App\Models\Question;
use App\Models\Teacher;
use App\Models\Test;
use App\Notifications\TestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function question(Classroom $classroom,lesson $lesson,Test $test)
    {
        $questions = Question::where('test_id',$test->id)->orderBy('id','ASC')->get();
        // dd($questions);
        return view('pages.teacher.test.question.index',compact(
            'classroom',
            'lesson',
            'test',
            'questions',
        ));
    }

    public function importExcelQuestion(Request $request,Classroom $classroom,lesson $lesson,Test $test)
    {
       

        if($test->start_test == null && $test->deadline_test == null && $test->status == 0){
            $this->validate($request,[ 
                'file' => 'required|mimes:csv,xlsx,xls',
                'start_test' => 'required|date|after:'.Carbon::now(),
                'deadline_test' => 'required|date|after:start_test|before_or_equal:'.Carbon::parse($request->start_test)->addDay(1),
            ]);
            
            if($request->hasFile('file')){
                $path = $request->file('file');            
                $nama_file = rand().$path->getClientOriginalName();
                $path->move('excel_file', $nama_file);
    
                $excel = Excel::import(new QuestionImport,public_path('/excel_file/'.$nama_file));
                

            }

            $test->update([
                'start_test' => $request->start_test,
                'deadline_test' => $request->deadline_test
            ]);
        }

        else{

            $this->validate($request,[ 
                'file' => 'required|mimes:csv,xlsx,xls',
            ]);
            
            if($request->hasFile('file')){
                $path = $request->file('file');            
                $nama_file = rand().$path->getClientOriginalName();
                $path->move('excel_file', $nama_file);
    
                $excel = Excel::import(new QuestionImport,public_path('/excel_file/'.$nama_file));

                $ask = Question::where('test_id',$test->id)->get();

                if($ask->count() > 0){
                    $score = 100 / $ask->count();

                    if($ask->count() == 1 || $ask->count() == 2 || $ask->count() == 4 || $ask->count() == 5 || $ask->count() == 10 || $ask->count() == 20 || $ask->count() == 25 || $ask->count() == 50 || $ask->count() == 100){
                        $scoreEnd = $score;
                    }
                    else{
                        $scoreEnd =  number_format($score, 1, '.', "");
                    }
                    
                    foreach($ask as $item){
                        $item->update([
                            'score' =>  $scoreEnd,
                        ]);
                    }
                }
                
            }
        }
        return response()->json(['success' => true]);
    }
    public function Questioncreate(Classroom $classroom,lesson $lesson,Test $test)
    {
        $teachers = Teacher::all();
        
        $levelclasses = Levelclass::with(['classrooms' => function($q) use ($classroom){
        
                return $q->where('name','like','%'.substr($classroom->name,0,-1).'%');
        
            }])->where('id',$classroom->levelclass->id)->get();

            

        return view('pages.teacher.test.question.create',compact(
            'classroom',
            'lesson',
            'test',
            'teachers',
            'levelclasses'
            
        ));
    }

    public function store(Request $request,Classroom $classroom,lesson $lesson,Test $test)
    {
        // dd($request->all());
      

        if($test->start_test == null && $test->deadline_test == null && $test->status == 0){
            $this->validate($request,[ 
                'question.*' => 'required',
                'option1.*' => 'required|string',
                'option2.*' => 'required|string',
                'option3.*' => 'required|string',
                'option4.*' => 'required|string',
                'option5.*' => 'required|string',
                'answer.*' => 'required|string|in:A,a,B,b,C,c,D,d,E,e',
                'score.*' => 'required',
                'start_test' => 'required|date|after:'.Carbon::now(),
                'deadline_test' => 'required|date|after:start_test|before_or_equal:'.Carbon::parse($request->start_test)->addDay(1),
            ]);

            foreach ($request->question as $key => $question) {
                $data = [
                    'ask' => $question,
                    'test_id' => $test->id,
                    'slug' => Str::random(10),
                    'option1' => $request->option1[$key],
                    'option2' => $request->option2[$key],
                    'option3' => $request->option3[$key],
                    'option4' => $request->option4[$key],
                    'option5' => $request->option5[$key],
                    'answer'  => strtoupper($request->answer[$key]),
                    'score' => $request->score[$key]
    
                ];
                
                Question::create($data);
            }
            

            $classroomHasTest = Classroom::with(['tests','students','lessons' => function($q) use ($lesson) {
                $q->where('name',$lesson->name);
            }])
        
            ->whereHas('tests',function($q) use ($test) {
                $q->where('test_id',$test->id);
            })->get();
            
            foreach($classroomHasTest as $cl){
                foreach($cl->students as $student){
                    foreach($cl->lessons as $lessonNotif){
                        $student->user->notify(new TestNotification($lessonNotif,$test));
                    }
                }
            }

            $test->update([
                'start_test' => $request->start_test,
                'deadline_test' => $request->deadline_test
            ]);
        }

        else{
            $this->validate($request,[ 
                'question.*' => 'required',
                'option1.*' => 'required|string',
                'option2.*' => 'required|string',
                'option3.*' => 'required|string',
                'option4.*' => 'required|string',
                'option5.*' => 'required|string',
                'answer.*' => 'required|string|in:A,a,B,b,C,c,D,d,E,e',
                'score.*' => 'required',
            ],[
                'question.*.required' => 'Tidak boleh kosong! wajib di isi',
                'option1.*.required' => 'Tidak boleh kosong! wajib di isi',
                'option2.*.required' => 'Tidak boleh kosong! wajib di isi',
                'option3.*.required' => 'Tidak boleh kosong! wajib di isi',
                'option4.*.required' => 'Tidak boleh kosong! wajib di isi',
                'option5.*.required' => 'Tidak boleh kosong! wajib di isi',
                'answer.*.required' => 'Tidak boleh kosong! wajib di isi',
                'answer.*.in' => 'Tidak ada kata :input, Pilih A , B , C, D ,E ',
                'score.*.required' => 'Tidak boleh kosong! wajib di isi'
            ]);

            foreach ($request->question as $key => $question) {
                $data = [
                    'ask' => $question,
                    'test_id' => $test->id,
                    'slug' => Str::random(10),
                    'option1' => $request->option1[$key],
                    'option2' => $request->option2[$key],
                    'option3' => $request->option3[$key],
                    'option4' => $request->option4[$key],
                    'option5' => $request->option5[$key],
                    'answer'  => strtoupper($request->answer[$key]),
                    'score' => $request->score[$key]
    
                ];
                
                Question::create($data);

                $question = Question::where('test_id',$test->id)->get();

                foreach($question as $item){
                    $item->update([
                        'score' => $request->score[$key]
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function questionView(Classroom $classroom,lesson $lesson,Test $test)
    {
        $questions = Question::where('test_id',$test->id)->get();
        return view('pages.teacher.test.question.questionView',compact(
            'classroom',
            'lesson',
            'test',
            'questions'
        ));
    }
    public function questionCheck(Classroom $classroom,lesson $lesson,Test $test)
    {
        $questions = Question::where('test_id',$test->id)->get();

        return response()->json(['questions' => $questions]);
    }
    public function edit(Classroom $classroom,lesson $lesson,Test $test,Question $question,$no)
    {

        return view('pages.teacher.test.question.edit',compact(
            'classroom',
            'lesson',
            'test',
            'question',
            'no'
        ));
    }
    public function update(Request $request,Classroom $classroom,lesson $lesson,Test $test,Question $question,$no)
    {
        $this->validate($request,[ 
            'question' => 'required',
            'option1' => 'required|string',
            'option2' => 'required|string',
            'option3' => 'required|string',
            'option4' => 'required|string',
            'option5' => 'required|string',
            'answer' => 'required|string|in:A,a,B,b,C,c,D,d,E,e',
            'score' => 'required',
        ],[
            'question.required' => 'Tidak boleh kosong! wajib di isi',
            'option1.required' => 'Tidak boleh kosong! wajib di isi',
            'option2.required' => 'Tidak boleh kosong! wajib di isi',
            'option3.required' => 'Tidak boleh kosong! wajib di isi',
            'option4.required' => 'Tidak boleh kosong! wajib di isi',
            'option5.required' => 'Tidak boleh kosong! wajib di isi',
            'answer.required' => 'Tidak boleh kosong! wajib di isi',
            'answer.in' => 'Tidak ada kata :input, Pilih A , B , C, D ,E ',
            'score.required' => 'Tidak boleh kosong! wajib di isi'
        ]);


        $question->update([
            'ask' => $request->question,
            'option1' => $request->option1,
            'option2' => $request->option2,
            'option3' => $request->option3,
            'option4' => $request->option4,
            'option5' => $request->option5,
            'answer' => $request->answer,
            'score' => $request->score,
        ]);
        

        return response()->json(['success' => true]);

    }
    public function destroy(Classroom $classroom,lesson $lesson,Test $test,Question $question)
    {
        $askCheck = Question::where('test_id',$test->id)->get();

        if($askCheck->count() == 1){
            return response()->json(['message' => 'Tidak bisa delete semua pertanyaan karna test sudah di buat, minimal 1 soal']);
       
        }
        else{
            $question->delete();

            if($question){

                $ask = Question::where('test_id',$test->id)->get();

                if($ask->count() > 0){
                    $scoreEnd = 0;

                    $score = 100 / $ask->count();

                    if($ask->count() == 1 || $ask->count() == 2 || $ask->count() == 4 || $ask->count() == 5 || $ask->count() == 10 || $ask->count() == 20 || $ask->count() == 25 || $ask->count() == 50 || $ask->count() == 100){
                        $scoreEnd = $score;
                    }
                    else{
                        $scoreEnd =  number_format($score, 1, '.', "");
                    }

                    
                    foreach($ask as $item){
                        $item->update([
                            'score' =>  $scoreEnd,
                        ]);
                    }
                }
            
                return response()->json(['message' => true]);
            }
        }
    }


    public function destroySelect(Request $request,Classroom $classroom,lesson $lesson,Test $test)
    {
        $question = Question::where('test_id',$test->id)->get();

        $selectDelete = Question::where('test_id',$test->id)->whereIn('id',$request->del);
    

        if($question->count() - count($request->del) == 0){

            return response()->json(['message' => 'Tidak bisa delete semua pertanyaan karna test sudah di buat, minimal 1 soal']);
        
        }
        else{
                
                $selectDelete->delete();    
            
                $ask = Question::where('test_id',$test->id)->get();
            
                if($ask->count() > 0){
                    $scoreEnd = 0;
    
                    $score = 100 / $ask->count();
    
                    if($ask->count() == 1 || $ask->count() == 2 || $ask->count() == 4 || $ask->count() == 5 || $ask->count() == 10 || $ask->count() == 20 || $ask->count() == 25 || $ask->count() == 50 || $ask->count() == 100){
                        $scoreEnd = $score;
                    }
                    else{
                        $scoreEnd =  number_format($score, 1, '.', "");
                    }

                    foreach($ask as $item){
                        $data =[
                            'score' => (int) $scoreEnd
                        ];
                        
                        $item->update($data);
    
                    }
                }

                return response()->json(['message' => true]);
        }
    }
}
