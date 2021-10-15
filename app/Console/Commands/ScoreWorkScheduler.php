<?php

namespace App\Console\Commands;

use App\Models\Answerwork;
use App\Models\Materialwork;
use App\Models\Student;
use App\Models\LessonTeacher;
use App\Models\Work;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ScoreWorkScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command when field now equal to due , and then automatic insert score in materialwork table';

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
        $works = Work::with(['materialworks','teacher','teacher.lessons'])->where('type','TUGAS')->get();
        
        $classroom =  array();
        foreach($works  as $work){
            $classroom[] = LessonTeacher::select('classroom_id')->where('lesson_id',$work->lesson_id)->where('teacher_id',$work->teacher_id)->groupBy('classroom_id')->get();
        }

        $studentWorkCheck = Student::with(['materialworks','classroom'])->whereIn('classroom_id',$classroom[0]->pluck('classroom_id'))->get();
        
        foreach($works as $work) {
            if($now > $work->due){
                foreach($studentWorkCheck as $student){
                    if($student->materialworks()->where('work_id',$work->id)->count() == 0){

                        $materialworkCreate = Materialwork::create([
                            'slug' => Str::random(10),
                            'status' => 'Tidak di kerjakan',
                            'material_file' => '-',
                            'work_id' => $work->id,
                        ]);
    
                        $answer = Answerwork::create([
                            'slug' => Str::random(10),
                            'score' => 0,
                            'materialwork_id' => $materialworkCreate->id
                        ]);
    
                        $materialworkCreate->students()->sync($student->id);
                    }
                }
            }
        }
    }
}
