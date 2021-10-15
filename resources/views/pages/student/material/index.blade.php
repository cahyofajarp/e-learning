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
                Materi
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                {{ auth()->user()->student->classroom->levelclass->name }} {{ auth()->user()->student->classroom->name }}
            </a>
        </li>

        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#000" href="">
               {{ $lesson->name }}
            </a>
        </li>

    </ol>
</nav>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
             
                <div class="table-responsive">
                    <table class="table table-striped" id="myTable" style="width:150%">
                        <thead class="btn-custom-green-blue">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th style="width:20%">Title </th>
                                <th style="width: 10%">Tipe Pekerjaan</th>
                                <th style="width:15%">Created At</th>
                                <th style="width:15%">File Work</th>
                                <th style="width:10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>

                                @foreach ($material as $work)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{!! $work->title !!}</td>
                                    <td><span class="badge badge-info">{{ $work->type }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($work->created_at)->isoFormat('dddd, D MMMM Y HH:mm') }}</td>
                                    <td>
                                        @if ($work->fileworks->count() > 0)
                                            <button class="btn btn-custom-green" style="font-size: 15px"> <i class="mdi mdi-check"></i> OK ({{ $work->fileworks->count() }} File)</button>
                                        @else 
                                            <button class="btn btn-warning" style="font-size: 15px"> <i class="mdi mdi-close"></i> Tidak ada File</button>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('student.material.preview',[$lesson,$work]) }}" class="btn btn-info">
                                            <small><i class="mdi mdi-eye"></i> Lihat Materi</small>
                                        </a>
                                    </td>
                                </tr>
                                <?php $no = 1; ?>
                                @endforeach
                            </tbody>
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

palindrome('kodok');


function palindrome(value){
    for(let i = value.length; i > 0 ; i--){
        if(){}
    }
}

    $(document).ready(function() {
        var t = $('#myTable').DataTable( {
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