@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')
<style>
.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>


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
        <li class="breadcrumb-item" style="color:#6c757d">
            {{ $work->title }}
        </li>
        
        <li class="breadcrumb-item" style="color:black">
            Ranking
        </li>
        
    </ol>
</nav>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Peringkat Nilai Siswa. </h3>
                    <a href="{{ route('teacher.work.student.exportAllResultStudentWork',[$classroom,$lesson,$work]) }}" class="btn btn-custom-green"><i class="mdi mdi-export"></i> Export to Excel</a>
                    <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover myTable" style="width:110%">
                    <thead class="btn-info">
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>Score / Nilai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($studentData as $key => $student)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $student['name'] }}</td>
                                <td>{{ $student['score'] }}</td>
                                <td>
                                    @if ($student['score'] >= $student['standard'])
                                        <span class="badge badge-success"> Aman <i class="mdi mdi-check"></i></span>
                                    @else 
                                        <span class="badge badge-danger"> Tidak Aman <i class="mdi mdi-close"></i></span>
                                    @endif
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
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Siswa Lulus. </h3>
                    <a href="" class="btn btn-custom-green"><i class="mdi mdi-export"></i> Export to Excel</a>
                    <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover myTable" style="width:110%">
                    <thead class="btn-custom-green-blue">
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>Score / Nilai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $collection = collect($studentData);
                            $filter = $collection->where('score','>=',$work->standard);
                            $studentPassWork = $filter;

                        @endphp
                        <?php $no = 1; ?>
                        @foreach ($studentPassWork as $key => $student)
                        
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $student['name'] }}</td>
                                <td>{{ $student['score'] }}</td>
                                <td>
                                    @if ($student['score'] >= $student['standard'])
                                        <span class="badge badge-success"> Aman <i class="mdi mdi-check"></i></span>
                                    @endif
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
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Siswa Tidak Lulus. </h3>
                <a href="" class="btn btn-custom-green"><i class="mdi mdi-export"></i> Export to Excel</a>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover myTable" style="width:110%">
                    <thead class="btn-custom-red">
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>Score / Nilai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $collection = collect($studentData);
                            $filter = $collection->where('file','<>','-')->where('score','<=',$work->standard);
                            $studentPassWork = $filter;

                        @endphp
                        <?php $no = 1; ?>
                        @foreach ($studentPassWork as $key => $student)
                        
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $student['name'] }}</td>
                                <td>{{ $student['score'] }}</td>
                                <td>
                                    @if ($student['score'] >= $student['standard'])
                                        <span class="badge badge-danger"> Tidak Lulus <i class="mdi mdi-close"></i></span>
                                    @endif
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
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Tidak Mengikuti . </h3>
                    <a href="" class="btn btn-custom-green"><i class="mdi mdi-export"></i> Export to Excel</a>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover myTable" style="width:110%">
                    <thead class="btn-custom-yellow">
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>Score / Nilai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $collection = collect($studentData);
                            $filter = $collection->where('file','=','-')->where('score','<=',$work->standard);
                            $studentDidNotTakeTheExam = $filter;


                        @endphp
                        <?php $no = 1; ?>
                        @foreach ($studentDidNotTakeTheExam as $key => $student)
                        
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $student['name'] }}</td>
                                <td>{{ $student['score'] }}</td>
                                <td>
                                    @if ($student['score'] <= $student['standard'])
                                        <span class="badge badge-warning"> Tidak mengumpulkan tugas <i class="mdi mdi-alert"></i></span>
                                    @endif
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