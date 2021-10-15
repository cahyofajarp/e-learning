<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionImport implements ToCollection,WithHeadingRow
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {   
        
        Validator::make($rows->toArray(),[
            '*.test_code' => ['required','exists:tests,test_code'],
            '*.pertanyaan' => ['required'],
            '*.option1' => ['required'],
            '*.option2' => ['required'],
            '*.option3' => ['required'],
            '*.option4' => ['required'],
            '*.option5' => ['required'],
            '*.answer' => ['required','in:A,a,B,b,C,c,D,d,E,e']
        ],[
            '*.test_code' => 'tidak boleh kosong',
            '*.pertanyaan' => 'tidak boleh kosong',
            '*.option1.required' => 'tidak boleh kosong!',
            '*.option2.required' => 'tidak boleh kosong!',
            '*.option3.required' => 'tidak boleh kosong!',
            '*.option4.required' => 'tidak boleh kosong!',
            '*.option5.required' => 'tidak boleh kosong!',
            '*.answer.required' => 'tidak boleh kosong!',
            '*.answer.in' => 'Tidak ada pilihan :input ,silahkan pilih A , B, C, D, E'

        ])->validate();

        $valScore = $rows->count();


        foreach ($rows as $key => $row) {

            $test = Test::where('test_code',$row['test_code'])->first();

            $score = 100 / $valScore;

            $data = [
                'test_id' => $test->id,
                'slug' => Str::random(10),
                'ask' => $row['pertanyaan'],
                'option1' => $row['option1'],
                'option2' => $row['option2'],
                'option3' => $row['option3'],
                'option4' => $row['option4'],
                'option5' => $row['option5'],
                'answer'  => $row['answer'],
                'score' => number_format($score, 1, '.', "")
            ];
            
            Question::create($data);


        }
    }
    
    public function customValidationMessages()
    {
        return [
            'option1.required' => 'Tidak boleh kosong wajib diisi!.',
            'option2.unique' => 'Tidak boleh kosong wajib diisi!',
        ];
    }
}
