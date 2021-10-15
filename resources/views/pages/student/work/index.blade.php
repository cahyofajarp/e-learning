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
                    <table class="table table-striped" id="myTable" style="width:180%">
                        <thead class="btn-custom-green-blue">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th style="width:20%">Title </th>
                                <th style="width: 10%">Tipe Pekerjaan</th>
                                <th style="width:15%">Created At</th>
                                <th style="width:15%">Due Time</th>
                                <th style="width:15%">File Work</th>
                                <th>Status</th>
                                <th style="width:10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($material->works->sortByDesc('created_at') as $work)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{!! $work->title !!}</td>
                                    <td><span class="badge badge-info">{{ $work->type }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($work->created_at)->isoFormat('dddd, D MMMM Y HH:mm') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($work->due)->isoFormat('dddd, D MMMM Y HH:mm') }}</td>
                                    <td>
                                        @if ($work->fileworks->count() > 0)
                                            <button class="btn btn-custom-green" style="font-size: 15px"> <i class="mdi mdi-check"></i> OK ({{ $work->fileworks->count() }} File)</button>
                                        @else 
                                            <button class="btn btn-warning" style="font-size: 15px"> <i class="mdi mdi-close"></i> Tidak ada File</button>
                                        @endif
                                    </td>
                                    <td>


                                        @if ($student->materialworks()->where('work_id',$work->id)->where('material_file','<>','-')->get()->count() > 0)
                                        
                                        <span class="btn btn-success btn-sm px-3 py-2" style="border-radius: 100px"><i class="mdi mdi-check"></i> Sudah di kerjakan</span>
                                        @elseif ($work->due < \Carbon\Carbon::now())
                                            <span class="btn btn-danger btn-sm px-3 py-2" style="border-radius: 100px""><i class="mdi mdi-clock"></i> Sudah Terlewat </span>
                                        @elseif(($student->materialworks()->where('work_id',$work->id)->where('material_file','-')->get()->count() <= 0)) 
                                            <span class="btn btn-warning btn-sm px-3 py-2" style="border-radius: 100px"><i class="mdi mdi-clock-alert"></i> Belum di kerjakan </span>
                                        @endif
                                   </td>
                                    <td>
                                        <a href="{{ route('student.work.preview',[$lesson,$work]) }}" class="btn btn-info mb-2">
                                            <small><i class="mdi mdi-eye"></i> Lihat Tugas</small>
                                        </a>
                                        <a href="{{ route('student.work.nilai',[$lesson,$work]) }}" class="btn btn-warning"> <small><i class="mdi mdi-eye"></i> Lihat Nilai</small></a>
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
    $(document).ready(function() {
        var t = $('#myTable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": [0,7]
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