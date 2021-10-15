<?php

namespace App\Exports;

use App\Models\Classroom;
use App\Models\Materialwork;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

class statistikExport implements FromView,ShouldAutoSize,WithEvents
{
    // use Exportable;
   protected $student;
   protected $classroom;

   public function __construct(Student $student,Classroom $classroom)
    {
        $this->student = $student;
        $this->classroom = $classroom;
    }

    
    public function view(): View
    {
        $st = $this->student;

        $materialwork = Materialwork::with(['work','students','answerwork'])
        ->whereHas('students',function($q) use ($st) {
            $q->where('students.id',$st->id);
        })
        ->get();
        
        return view('pages.teacher.dashboard.export.materialworkDataExport',[
            'materialwork' => $materialwork,
            'classroom' => $this->classroom,
            'student' => $this->student
        ]);
    }
    public function registerEvents(): array
    {
        
        // Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
        //     $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        // });

        return [
            AfterSheet::class => function(AfterSheet $event) {
                // $cellRange = 'A1:W1'; // All headers
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                // $event->sheet->styleCells(
                //     'A1:H1',
                //     [
                //         'borders' => [
                //             'allBorders' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                //                 'color' => ['argb' => 'EB2B02'],
                //             ],
                //         ]
                //     ]
                // );
            },
        ];
    }

}