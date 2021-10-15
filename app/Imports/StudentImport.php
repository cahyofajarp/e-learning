<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

    
class StudentImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {   

        foreach($rows as $row){
            
            $classrooms = Classroom::with(['levelclass' => function($q) use ($row) {
                
                return $q->where('name',$row['kelas']);
            
            }])->where('name',$row['jurusan'])->get();

            $data[] = $classrooms->pluck('id');
        }

        $array_unique = array_unique($data);
        
        for($i =0; $i < count($array_unique[0]); $i++){

            $student = Student::create([
                'slug' => Str::random(8),
                'name' => $rows[$i]['nama_siswa'],
                'classroom_id' => $array_unique[0][$i],
                'registration_number' => $rows[$i]['no_registrasi'],
                'address' => $rows[$i]['alamat'],
            ]);

            $user = User::create([
                'name' => $student->name,
                'email' => strtolower(str_replace(' ','_',$student->name)).'@gmail.com',
                'password' => bcrypt('12345678'),   
                'roles' => 'siswa',
                'student_id' => $student->id
            ]);

        }
    }
}
