<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeacherImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row){


            // echo '0'.$row['no_handphone'].' ';
            $teacher = Teacher::create([
                'name' => $row['nama_guru'],
                'slug' => Str::random(10),
                'NIP' => $row['nip'],
                'alamat' => $row['alamat'],
                'no_hp' => '0'.$row['no_handphone'],
                'tanggal_lahir' =>  Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir']))->format('Y-m-d'),
                'last_education' => $row['pendidikan_terakhir']
            ]);

            $user = User::create([
                'name' => $teacher->name,
                'email' => strtolower(str_replace(' ','_',$teacher->name)).'@gmail.com',
                'password' => bcrypt('12345678'),  
                'roles' => 'guru',
                'teacher_id' => $teacher->id
            ]);

        }
    }
}
