<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Cahyo Fajar P',
            'roles' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password')
        ]);
        
        $teacher = Teacher::create([
            'name'           => 'Ahmad Hilal S.KOM',
            'NIP'            => '232390209',
            'slug'           => Str::random(8),
            'alamat'         => 'Jl.Kp Baru I',
            'no_hp'          => '81231149457',
            'tanggal_lahir'  => '2000-05-28',
            'last_education' => 'S1 INFORMATIKA',
            'avatar'         => null
        ]);

        $guru = User::create([
            'name' => 'Ahmad Hilal',
            'roles' => 'guru',
            'email' => 'hilal@gmail.com',
            'teacher_id' => $teacher->id,
            'password' => bcrypt('password')
        ]);


    }
}
