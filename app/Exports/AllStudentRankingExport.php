<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\Exportable;

class AllStudentRankingExport implements FromQuery
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $user = User::all();

        return $user;
    }

    // public function headings(): array
    // {
    //     return [

    //     ];
    // }

    // public function map($user): array
    // {
    //     return [

    //     ];
    // }

}
