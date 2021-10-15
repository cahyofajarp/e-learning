<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\TeacherImport;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{

   

    public function index()
    {
        $teachers = Teacher::latest()->get();
        return view('pages.admin.teacher.index',compact(
            'teachers'
        ));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'           => 'required',
            'NIP'            => 'required|numeric',
            'alamat'         => 'required',
            'no_hp'          => 'required|numeric',
            'tanggal_lahir'  => 'required|date',
            'last_education' => 'required',
            'avatar'         => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        
        $data = $request->all();
        $avatar = null;
        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar')->store('teacher','public');
        }
        $data['slug'] = Str::random(10);
        $data['avatar'] = $avatar;
        
        $teacher = Teacher::create($data);

        $user = User::create([
            'name' => $teacher->name,
            'email' => strtolower(str_replace(' ','_',$teacher->name)).'@gmail.com',
            'password' => bcrypt('12345678'),   
            'images' =>  $avatar,
            'roles' => 'guru',
            'teacher_id' => $teacher->id
        ]);

       return response()->json(['success' => true]);
        
    }

    public function edit(Teacher $teacher)
    {
        return response()->json(['teacher' => $teacher]);
    }

    public function update(Request $request,Teacher $teacher)
    {
        $this->validate($request,[
            'name'           => 'required',
            'NIP'            => 'required|numeric|unique:teachers,NIP,'.$teacher->id,
            'alamat'         => 'required',
            'no_hp'          => 'required|numeric',
            'tanggal_lahir'  => 'required|date',
            'last_education' => 'required',
            'avatar'         => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $avatar = $teacher->avatar ?? null;
        if($request->hasFile('avatar')){
            if($teacher->avatar){
                Storage::disk('local')->delete('public/'.$teacher->avatar);
            }
            $avatar = $request->file('avatar')->store('teacher','public');
        }


        $teacher->update([
            'name'           => $request->name,
            'NIP'            => $request->NIP,
            'alamat'         => $request->alamat,
            'no_hp'          => $request->no_hp,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'last_education' => $request->last_education,
            'avatar'         => $avatar
        ]);


        return response()->json(['success' =>  true]);

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

                $excel = Excel::import(new TeacherImport,public_path('/excel_file/'.$nama_file));
                
                return response()->json([
                    'success' => true
                ]);
            }
       } catch (\Exception $th) {
            return response()->json(['error' => true,'message' => 'Oops, Maaf data yang anda masukan tidak sesuai / Duplicate data.']);
       }     
    }
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        Storage::disk('local')->delete('public/'.$teacher->avatar);
    }
}
