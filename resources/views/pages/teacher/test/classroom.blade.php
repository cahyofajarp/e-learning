@extends('app.app')
@push('add-style')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

@endpush
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning">
            <H4>Noted : </H4>
            <small>Kelas ini adalah kelas yang anda ajar!, Silahkan lihat data test untuk lihat selengkap nya. <br>Good Luck!</small>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped" id="myTable">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th>Level Class</th>
                                <th>Nama Jurusan + Kelas</th>
                                <th>Jumlah Mata Pelajaran</th>
                                <th style="width: 25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no= 1; ?>
                            @foreach ($lessonTeacher as $lessonteacher)
                                <?php $no = 1; ?>
                                @foreach ($lessonteacher->classroom()->get() as $classroom)
                                  <tr>
                                      <td>{{ $no }}</td>
                                      <td>{{ $classroom->levelclass->name }}</td>
                                      <td>{{ $classroom->name }}</td>
                                      <td>
                                        <button class="btn btn-warning btn-sm px-3 py-2" style="border-radius: 100px">
                                            <i class="mdi mdi-book"></i>
                                            {{ $lessonteacher->where('teacher_id',auth()->user()->teacher_id)->where('classroom_id',$classroom->id)->count('classroom_id') }}  Pelajaran
                                        </button></td>
                                      <td><a href="{{ route('teacher.test.lesson',$classroom) }}" class="btn btn-custom-green px-3 py-2 btn-sm" style="border-radius: 100px"> <i class="mdi mdi-eye"></i> Lihat Detail Test</a></td>
                                  </tr>
                                <?php $no++; ?>
                                @endforeach
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