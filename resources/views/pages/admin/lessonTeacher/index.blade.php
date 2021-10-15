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
                Learning Managemnt
            </a>
        </li>
        <li class="breadcrumb-item" style="color:black">
            Lesson Teacher
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <h5>Noted :</h5>
            <p style="font-size:14px">Jika tidak ada pelajarannya , silahkan isi pelajaran terlebih dahulu di menu <a href="">Lesson!</a></p>
        </div>
        <div class="card">
            <div class="card-body">
                
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped" id="myTable" style="width:120%">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th>Level Class</th>
                                <th>Nama Jurusan + Kelas</th>
                                <th>Status</th>
                                <th>Jumlah Pelajaran</th>
                                <th>Jumlah Guru</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;  ?>
                            @foreach ($classrooms->sortBy('name') as $classroom)
                                <tr>    
                                    <td class="text-center">{{ $no }}</td>
                                    <td>{{ $classroom->levelclass->name }}</td>
                                    <td>{{ $classroom->name }}</td>
                                    <td>
                                        @if ($classroom->lessons()->count() > $LessonTeacher->where('classroom_id',$classroom->id)->count())
                                            {{-- <i class="mdi mdi-checkbox-marked-outline" style="color:green"></i>  --}}
                                            <i class="mdi mdi-alert-circle" style="color:#ffc107"></i> <small>Belum Lengkap</small>
                                        @elseif($classroom->lessons()->count() > 0 && $classroom->lessons()->count() == $LessonTeacher->where('classroom_id',$classroom->id)->count())
                                            
                                            <i class="mdi mdi-checkbox-marked-outline" style="color:green"></i> <small>Sudah Lengkap</small> 
                                        @else
                                            <i class="mdi mdi-close-circle" style="color: red"></i> <small>Tidak Ada Mapel</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{$classroom->lessons()->count() }} Pelajaran
                                    </td>
                                    <td>
                                        {{ $LessonTeacher->where('classroom_id',$classroom->id)->count() }} Guru
                                    </td>
                                    <td>
                                        @if ($classroom->lessons()->count() > 0)
                                            <a href="{{ route('admin.lessonTeacher.create',$classroom) }}" class="btn-custom-green btn btn-sm mb-2"> <i class="mdi mdi-plus"></i> Tambah Guru</a>
                                            <a href="" class="btn-info btn btn-sm"> <i class="mdi mdi-application"></i> Preview</a>
                                        @else     
                                            <a href="" class="btn btn-sm btn-custom-red"><i class="mdi mdi-close-circle"></i> Mapel Kosong</a>
                                        @endif
                                    </td>
                                </tr>
                                <?php $no++;  ?>
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