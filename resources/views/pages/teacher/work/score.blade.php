@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')

<nav aria-label="Page breadcrumb">
    <ol class="breadcrumb" style="background: white">
        <li class="breadcrumb-item" aria-current="page">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.home') }}"><i class="mdi mdi-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                Work Management
            </a>
        </li> 
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                {{ $classroom->levelclass->name }} {{ $classroom->name }}
            </a>
        </li>
        <li class="breadcrumb-item" style="color:#6c757d">
            {{ $lesson->name }}
        </li>
        <li class="breadcrumb-item" style="color:black">
            Score
        </li>

    </ol>
</nav>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body alert-success">
                <p>Judul Tugas  : <b>{{ $work->title }}</b>.</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card text-white" style="border-radius: 100px;background:#3f51b5">
            <div class="card-body">
                <h3 class="text-center">TOTAL SISWA KELAS  - {{ $classroom->levelclass->name }} {{ $classroom->name }} :  <b> {{ $classroom->students()->count() }} Siswa.</b></h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    
    <div class="col-md-12 mb-5">
        <h5><a href="{{ route('teacher.work.student.ranking',[$classroom,$lesson,$work]) }}" style="color:#3f51b5"> <i class="mdi mdi-arrow-right"></i> Lihat Peringkat Tugas</a></h5>
    </div>
    <div class="col-md-6">
        <div class="alert-info mb-2">
            <div class="card-body">
                <p>Siswa yang sudah mengumpulkan <b>{{ $studentWorkDone }}</b> Siswa</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table  table-hover table-striped myTable" style="width:230%">
                        <thead class="">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th style="width:23%">Nama Siswa</th>
                                <th>Status Penilaian</th>
                                <th style="width:20%">Nama Mata Pelajaran </th>
                                <th>Tipe Pekerjaan</th>
                                <th style="width:40%">Due Time</th>
                                <th style="width:0%">Status</th>
                                <th style="width:25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($materialWork as $material)
                                @foreach ($material->students as $student)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>
                                        @if ($material->answerwork == '' )
                                            <span class="badge badge-warning">Belum Dinilai</span>
                                        @else 
                                            <span class="badge badge-success">Sudah Dinilai</span>
                                        @endif
                                    </td>
                                    <td>{{ $lesson->name }}</td>
                                    <td><span class="badge badge-info">{{ $material->work->type }}</span></td>
                                    
                                    <td>{{ $material->work->due }}</td>
                                    <td><span class="badge badge-success">{{ $material->status }}</span></td>
                                    <td>
                                        <a href="{{ route('teacher.work.student.giveScore',[$classroom,$lesson,$work,$student]) }}" class="btn btn-sm btn-custom-green px-3"><i class="mdi mdi-pencil"></i> Nilai </a>
                                    </td>
                                </tr>
                            
                            <?php $no++ ?>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="alert-warning mb-2">
            <div class="card-body">
                <p>Siswa yang belum mengumpulkan Tugas <b> {{ $studentWorkNotYet }} </b> Siswa</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                
                <div class="table-responsive">
                    
                    <table class="table  table-hover table-striped myTable" style="width:250%">
                        <thead class="">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th style="width:23%">Nama Siswa</th>
                                <th>Status Penilaian</th>
                                <th style="width:20%">Nama Mata Pelajaran </th>
                                <th>Tipe Pekerjaan</th>
                                <th style="width:40%">Due Time</th>
                                <th style="width:0%">Status</th>
                                <th style="width:35%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                                @if ($studentWorkDataNotYet)
                                    @foreach ($studentWorkDataNotYet as $student)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td> 
                                            @php
                                                $score = $student->materialworks->where('work_id',$work->id)->first();
                                            @endphp
                                           @if ($student->materialworks->where('work_id',$work->id)->first())
                                                @if ($score->material_file == '-' && $score->answerwork->score  == 0)
                                                    <span class="badge badge-warning">Tidak Mengumpulkan</span>
                                                @endif
                                            @else
                                                <span class="badge badge-warning">Tugas Belum Berakhir</span>
                                           @endif
                                        </td>
                                        <td>{{ $lesson->name }}</td>
                                        <td><span class="badge badge-info">{{ $work->type }}</span></td>
                                        
                                        <td>{{ $work->due }}</td>
                                        <td><span class="badge badge-danger">Belum Dikerjakan</span></td>
                                        <td>
                                            <a href="{{ route('teacher.work.student.giveScore',[$classroom,$lesson,$work,$student]) }}" class="btn btn-sm btn-custom-red px-3"><i class="mdi mdi-pencil"></i> Nilai </a>
                                   
                                        </td>
                                    </tr>

                                    @php
                                        $no++;
                                    @endphp
                                    @endforeach
                                @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
@endsection

@push('add-script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>


<script>
@if (Session::has('error'))
    Swal.fire(
        'Error!',
        '{{ Session::get("error") }}',
        'error'
    )

    setTimeout(function() {
        location.reload();
    },500)

@endif

$(document).ready(function() {
    var t = $('.myTable').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": []
    } );

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
});
</script>
@endpush