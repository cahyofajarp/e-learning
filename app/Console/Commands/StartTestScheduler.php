<?php

namespace App\Console\Commands;

use App\Models\Test;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class StartTestScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command when field start_date test lebih besar';

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
        $tests = Test::latest()->whereNotNull('start_test')->whereNotNull('deadline_test')->get();
        $now  = Carbon::now();
        foreach($tests  as $test){
            
            $until = Carbon::parse($test->deadline_test);
       
            
            if($now >= $test->start_test){
                $test->update([
                    'status' => 1
                ]);

                if($now > $until){
                    $test->update([
                        'status' => 2
                    ]);
                }
            }
        }
           
    }
}
