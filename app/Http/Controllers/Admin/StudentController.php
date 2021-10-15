<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {   $classrooms = Classroom::all();
        $students = Student::with('classroom')->latest()->get();
        
        return view('pages.admin.student.index',compact(
            'students',
            'classrooms'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'registration_number' => 'required|numeric|unique:students,registration_number',
            'address' => 'required',
            'classroom_id' => 'required|integer|exists:classrooms,id',
            'images' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $data = $request->all();
        $avatar = null;
        if($request->hasFile('images')){
            $avatar = $request->file('images')->store('student','public');
        }
        $data['slug'] = Str::random(10);
        $data['images'] = $avatar;

        $student = Student::create($data);

        
        $user = User::create([
            'name' => $student->name,
            'roles' => 'siswa',
            'student_id' => $student->id,   
            'email' => strtolower(str_replace(' ','_',$student->name)).'@gmail.com',
            'password' => bcrypt('12345678'),   
            'images' =>  $avatar,
            'student_id' => $student->id
        ]);

        
        return response()->json([
            'success' => true
        ]);
        
    }


    public function importExcel(Request $request)
    {
        $this->validate($request,[
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);
        
     
       try {
            if($request->hasFile('file')){
                $path = $request->file('file');            
                $nama_file = rand().$path->getClientOriginalName();
                $path->move('excel_file', $nama_file);

                $excel = Excel::import(new StudentImport,public_path('/excel_file/'.$nama_file));
                
                return response()->json([
                    'success' => true
                ]);
            }
       } catch (\Exception $th) {
            Session::flash('error','Oops, Maaf data yang anda masukan tidak sesuai');
            return response()->json(['error' => true,'message' => 'Oops, Maaf data yang anda masukan tidak sesuai / Duplicate data.']);
       }     
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {   
        $students = Student::with(['classroom'])->latest()->get();

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadview('pages.admin.student.exportPDF',compact('students'))->setPaper('a4','landscape');
        return $pdf->stream();

        // return view('pages.admin.student.exportPDF',compact(
        //     'students'
        // ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $students = Classroom::with(['levelclass' => function($q) {
            return $q->orderBy('name','ASC');
        }])->get();

        return response()->json([
            'student' => $student,
            'students' => $students
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $this->validate($request,[
            'name' => 'required',
            'registration_number' => 'required|numeric|unique:students,registration_number,'.$student->id,
            'address' => 'required',
            'classroom_id' => 'required|integer|exists:classrooms,id',
            'images' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $avatar = $student->images ?? null;
        if($request->hasFile('images')){
            if($student->images){
                Storage::disk('local')->delete('public/'.$student->images);
            }

            $avatar = $request->file('images')->store('student','public');
        }

        $student->update([
            'name' => $request->name,
            'classroom_id' => $request->classroom_id,
            'registration_number' => $request->registration_number,
            'address' => $request->address,
            'images' => $avatar
        ]);

        return response()->json(['success'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        
        $student->delete();
        $avatar = Storage::disk('local')->delete('public/'.$student->images);

    }
}
