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
            <a class="text-gray" style="color:#000" href="">
                {{ auth()->user()->student->classroom->levelclass->name }} {{ auth()->user()->student->classroom->name }}
            </a>
        </li>

    </ol>
</nav>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-striped" id="myTable" style="width: 100%">
                        <thead class="btn-custom-green-blue">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Nama Pelajaran</th>
                                <th>Jumlah Materi</th>
                                <th>Dibuat Oleh</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($lessons as $lesson)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td> <i class="mdi mdi-book"></i> MATERI</td>
                                    <td>{{ $lesson->name }}</td>
                                    <td>
                                        <button class="btn btn-warning">{{ $lesson->works()->where('type','MATERI')->where('lesson_id',$lesson->id)->count('type') }} Materi</button>
                                    </td>
                                    <td>
                                        {{ $lesson->teachers()->where('lesson_id',$lesson->id)->first()->name }} 
                                    </td>
                                    <td>
                                        <a href="{{ route('student.material.index',$lesson) }}" class="btn btn-custom-green text-white pointer" style="cursor: pointer;"><small> <i class="mdi mdi-eye"></i> Lihat Detail Materi</small></a>
                                    </td>
                                </tr>
                                <?php $no++; ?>
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