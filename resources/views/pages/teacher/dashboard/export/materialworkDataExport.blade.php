<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <style>
        .btn-warning {
            color: #212529;
            background-color: #fdc16a;
            border-color: #fdc16a;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #e8eef3;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f4f8fb;
        }
        
        .table td, .table th {
            padding: 1rem;
            vertical-align: top;
            border-top: 1px solid #e8eef3;
        }
        </style>
        <table class="table table-striped table-hover table-responsive" style="width: 140%">
            <thead class="btn-warning" style=" color: #212529;
            background-color: #fdc16a;
            border-color: #fdc16a;">
                <tr>
                    <th  style="width: 3%">#</th>
                    <th>Nama Siswa</th>
                    <th><b>Title Tugas</b></th>
                    <th><b>Tanggal Tugas Dibuat</b></th>
                    <th><b>Tanggal Tugas Dikirim</b></th>
                    <th><b>Tanggal Batas Tugas</b></th>
                    <th><b>Status Pengerjaan</b></th>
                    <th><b>Score</b></th>
                </tr>
            </thead> 
            <tbody>
                <?php $no =1 ; ?>
                    @foreach ($materialwork as $material)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $material->work->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($material->work->created_at)->isoFormat('D MMMM Y - HH:mm') }}</td>
                        <td>
                            @php
                                $send = '-';
        
                                if($material->material_file != '-'){
                                    $send =  \Carbon\Carbon::parse($material->created_at)->isoFormat('D MMMM Y - HH:mm'); 
                                }
                            @endphp
                            {{ $send }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($material->due)->isoFormat('D MMMM Y - HH:mm') }}
                        </td>
                        <td>
                            @if ($material->material_file == '-')
                                <span class="badge badge-danger badge-lg"><i class="mdi mdi-close"></i> Not Finished</span>
                            @else 
                                <span class="badge badge-success"><i class="mdi mdi-check"></i> Done</span>
                            @endif
                        </td>
                        <td>    
                            @php
                                $color = 'red';
        
                                if( $material->answerwork->score >= $material->work->standard){
                                    $color = 'green';
                                }
                            @endphp
                            <h4 class="text-center" style="border-bottom: 1px dotted {{ $color }} ;color:{{ $color }};font-weight:bold">{{ $material->answerwork->score }}</h4>
                        </td>
                    </tr>
                    
                <?php $no++ ; ?>
                    @endforeach
            </tbody>
        </table>
</body>
</html>