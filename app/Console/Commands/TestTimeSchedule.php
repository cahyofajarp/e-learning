<?php

namespace App\Console\Commands;

use App\Models\Answer;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\Result;
use App\Models\Student;
use App\Models\Test;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class TestTimeSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Time Deadline test aumatic create result zero if don"t work ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $now = \Carbon\Carbon::now();
        
        $tests = Test::with(['classrooms'])->whereNotNull('start_test')->whereNotNull('deadline_test')->get();
        
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
                // }
            }
        }
    }
}
