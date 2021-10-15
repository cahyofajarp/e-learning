@extends('app.app')
@push('add-style')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

@endpush
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped" id="myTable" style="width: 100%">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0; ?>
                            @foreach ($classroom->students as $student)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $classroom->name }}</td>
                                    <td>
                                        <a href="{{ route('teacher.statistik.student.statistik',[$classroom,$lesson,$student]) }}" class="btn btn-success px-4 py-1" style="border-radius: 100px">
                                            <small><i class="mdi mdi-signal"></i> Statistik</small>
                                        </a>
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