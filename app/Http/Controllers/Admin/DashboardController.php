<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\lesson;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::count();
        $teacher = Teacher::count();
        $classroom = Classroom::count();
        $lesson = lesson::count();

        $year = Carbon::now()->format('Y');

        // $json = User::whereYear('created_at',$year)->whereIn(DB::raw('MONTH(created_at)'), [01,02,03,04,05,06,08])->get();
        $users = User::select('id', 'created_at')
        ->whereYear('created_at',$year)
        ->get()
        ->groupBy(function($date) {
            //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });
        
        $usermcount = [];
        // $userArr = [];
        
        foreach ($users as $key => $value) {
            $usermcount[(int)$key] = count($value);
        }
        
        $month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Agust','Sep','Oct','Nov','Desc'];
        
        for($i = 1; $i <= 12; $i++){
            if(!empty($usermcount[$i])){
                $userArr[$i] = ['month' => $month[$i -1],'value' => $usermcount[$i]];    
            }else{
                $userArr[$i] = ['month' => $month[$i -1],'value' => 0];    
            }
        }

        // $json = json_encode();
     
        // dd($userArr[1]);
        return view('pages.admin.dashboard.dashboard',compact(
            'user',
            'teacher',
            'classroom',
            'lesson',
            'userArr'
        ));
    }
}
