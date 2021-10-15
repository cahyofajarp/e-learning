@extends('app.app')
@push('add-style')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

@endpush
@section('content')

<nav aria-label="Page breadcrumb">
    <ol class="breadcrumb" style="background: white">
        <li class="breadcrumb-item" aria-current="page">
            <a class="text-gray" style="color:#6c757d" href="{{ route('teacher.home') }}"><i class="mdi mdi-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                Student Management
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                {{ $classroom->levelclass->name }} {{ $classroom->name }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                Statistik
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#000" href="">
                {{ $student->name }}
            </a>
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-6">
        <div class="alert alert-warning" style="border:2px dotted #126943">
            <p class="mt-3"><i class="mdi mdi-clipboard-list"></i>  Test Kelas <b>{{ $classroom->levelclass->name }} {{ $classroom->name }}</b></p>
            <small><i class="mdi mdi-clock"></i>Test Sedang Berlangsung </small><p>Nama :  {{ $student->name }}</p>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped myTable" style="width: 150%">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th>Nama Test</th>
                                <th>Status</th>
                                <th style="width: 30%">Action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php $no  =1; ?>
                           @foreach ($classroom->tests()->where('status',1)->get() as $test)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $test->name }}</td>
                                <td><span class="badge badge-success">Sedang Belangsung</span></td>
                        
                                <td>
                                    <a href="" class="btn btn-info px-4 btn-sm py-2" style="border-radius: 100px"><i class="mdi mdi-clipboard"></i> Detail Test</a>
                                </td>
                            </tr>
                            <?php $no++ ?>
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="alert alert-info" style="border:2px dotted #126943">
            <p class="mt-3"><i class="mdi mdi-clipboard-list"></i> History Test Kelas <b>{{ $classroom->levelclass->name }} {{ $classroom->name }}</b></p>
            <small><i class="mdi mdi-check"></i>Test Sudah Berakhir </small>
            <p>Nama :  {{ $student->name }}</p>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped myTable" style="width: 150%">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th>Nama Test</th>
                                <th>Status</th>
                                <th>Status Test</th>
                                <th style="width: 30%">Action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php $no  =1; ?>
                           @foreach ($classroom->tests()->where('teacher_id',auth()->user()->teacher_id)->where('status',2)->get() as $test)
                        
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $test->name }}</td>
                                <td><span class="badge badge-danger">Sudah Berakhir</span></td>
                                <td><span class="badge badge-success">Dikerjakan</span></td>
                                <td>
                                    <a href="" class="btn btn-info px-4 btn-sm py-2" style="border-radius: 100px"><i class="mdi mdi-clipboard"></i> Detail Test</a>
                                </td>
                            </tr>
                            <?php $no++ ?>
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p class="text-center">- Other -</p>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <div class="alert alert-info" style="border:2px dotted #126943">
            <p class="mt-3"><i class="mdi mdi-clipboard-list"></i>  Tugas Kelas <b>{{ $classroom->levelclass->name }} {{ $classroom->name }}</b></p>
            <small><i class="mdi mdi-clock"></i> Sedang Berlangsung </small><p>Nama :  {{ $student->name }}</p>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped myTable" style="width: 120%">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th>Nama Tugas</th>
                                <th>Status Mengerjakan</th>
                                <th>On Time</th>
                                <th style="width: 25%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-success" style="border:2px dotted #126943">
            <p class="mt-3"><i class="mdi mdi-clipboard-list"></i> History Tugas Kelas <b>{{ $classroom->levelclass->name }} {{ $classroom->name }}</b></p>
            <small><i class="mdi mdi-check"></i> Sudah Berakhir </small>
            <p>Nama :  {{ $student->name }}</p>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped myTable" style="width: 120%">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th>Nama Tugas</th>
                                <th>Status Mengerjakan</th>
                                <th>On Time</th>
                                <th style="width: 25%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('add-script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>

<script>
    $(document).ready(function() {
        var t = $('.myTable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]
        } );
    
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
</script>

@endpush