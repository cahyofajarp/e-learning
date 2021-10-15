@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')

<div class="modal fade" id="modalCheckClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content p-2" style="background-color: white !important">
        <div class="modal-header">
            {{-- <img src="{{ asset('images/6663-min.jpg') }}" alt="" style="  
            width: 70%;
            margin: 0 auto;
            margin-right: -10px;
        "> --}}
            <small>Noted : Kelas ini adalah kelas yang akan mengikuti ujian.</small>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <table class="table table-striped">
                    <thead class="btn-custom-green">
                        <tr>
                            <th>#</th>
                            <th>Nama Kelas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                      
                    </tbody>
                </table>
          </div>
      </div>
    </div>
</div>
<nav aria-label="Page breadcrumb">
    <ol class="breadcrumb" style="background: white">
        <li class="breadcrumb-item" aria-current="page">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.home') }}"><i class="mdi mdi-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                Learning Management
            </a>
        </li> 
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                Test Management
            </a>
        </li> 
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                {{ $classroom->levelclass->name }} {{ $classroom->name }}
            </a>
        </li>
        <li class="breadcrumb-item" style="color:black">
            {{ $lesson->name }}
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.test.test.testCreate.create',[$classroom,$lesson]) }}" class="btn btn-custom-green mb-3">
                    <i class="mdi mdi-plus"></i> Tambah Test
                </a>
                <div class="table-responsive">
                    
                    <table class="table  table-hover table-striped" id="myTable" style="width:185%">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th style="width:20%">Nama Test</th>
                                <th>Nama Guru Pengajar</th>
                                <th>Test Code</th>
                                <th>Description</th>
                                <th>Nilai KKM</th>
                                <th>Waktu Ujian (Menit)</th>
                                <th style="width:20%">Waktu Test Di Mulai</th>
                                <th style="width:25%">Status Test</th>
                                <th style="width:20%">Kadaluarsa Test</th>
                                <th style="width: 40%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                           @foreach ($tests as $test)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $test->name }}</td>
                                    <td>{{ $test->teacher->name }}</td>
                                    <td><span class="btn btn-warning">{{ $test->test_code }}</span></td>
                                    <td>{{ $test->decription }}</td>
                                    <td>{{ $test->standard }}</td>
                                    <td>{{ $test->time }} Menit</td>
                                    <td>
                                        @if ($test->start_test == NULL)
                                            <span class="badge badge-info">Waktu tidak ada ! Buat soal <b>sekarang !</b></span>
                                        @else 
                                             {{ $test->start_test }}
                                        @endif

                                    </td>
                                    <td>
                                        @if ($test->status == 0)
                                            <span class="btn btn-warning" style="font-size: 14px;border-radius:100px"><i class="mdi mdi-alert-circle"></i> Test Belum Dimulai</span>
                                        @elseif ($test->status == 1)
                                            <span class="btn btn-custom-green" style="font-size: 14px;border-radius:100px"> <i class="mdi mdi-timer"></i> Test Sedang Dimulai</span>
                                        @else
                                            <span class="btn btn-danger" style="font-size: 14px;border-radius:100px"><i class="mdi mdi-check"></i> Test Telah Berakhir</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($test->start_test == NULL)
                                        <span class="badge badge-info">Waktu tidak ada ! Buat soal <b>sekarang !</b></span>
                                        @else 
                                            {{ $test->deadline_test }}
                                        @endif

                                    </td>
                                    <td>
                                        @if ($test->status == 0)
                                            <a href="{{ route('admin.test.test.testCreate.question',[$classroom,$lesson,$test]) }}" class="btn btn-sm btn-custom-blue mb-2"> <i class="mdi mdi-clipboard-list"></i>  Buat Soal</a>
                                        @else
                                            <a href="{{ route('admin.test.test.questionView',[$classroom,$lesson,$test]) }}" class="btn btn-sm btn-info mb-2"> <i class="mdi mdi-clipboard-list"></i>  Lihat Soal</a>
                                        @endif
                                        <button data-toggle="modal" data-target="#modalCheckClass" data-check="{{ route('admin.test.test.classTestCheck',[$classroom,$lesson,$test]) }}" id="btn-check" class="btn btn-custom-green btn-sm mb-2">
                                           <i class="mdi mdi-home"></i> Check Kelas</button> <br>
                                        <a href="" class="btn btn-custom-yellow btn-sm mb-2">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a><br>
                                        <button data-delete="{{ route('admin.test.test.testCreate.destroy',[$classroom,$lesson,$test]) }}" class="btn btn-custom-red btn-sm" id="btn-delete">
                                            <i class="mdi mdi-delete"></i> Delete
                                        </button>
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

function testMiddleware(){
    let getData = localStorage.getItem('data');

    $.get('/admin/test/{{ $classroom->slug }}/classroom/lesson/{{ $lesson->slug }}/testcode/'+getData,function(data){
        if(data.data != true){
            window.location.href = '{{ route("admin.test.test",$classroom) }}';
        }
    })  

    if(getData == '' || getData != '{{ $lesson->code }}'){ 
        window.location.href = '{{ route("admin.test.test",$classroom) }}';
        
    }  
}
testMiddleware();

$('button#btn-check').click(function() {
    // $('#modalCheckClass').modal('show');

    let url = $(this).data('check');
    $.ajax({
        type : 'GET',
        url : url,
        beforeSend:function(){
            $('#tbody').html('<i class="mdi mdi-spin mdi-loading"></i> Loading ....');
        },
        success :function (res){
            console.log(res.test.classrooms);

            let viewHtml = '';
            for(let i = 0; i < res.test.classrooms.length;i++){
                viewHtml += '<tr><td>'+(i+1)+'</td><td>'+res.test.classrooms[i].name+'</td><td><i class="mdi mdi-check-circle" style="color:#89ca5c"></i> Ok</td></tr>';
            }

            $('#tbody').html(viewHtml);
        }
    });

})    

$('button#btn-delete').click(function(e){
    e.preventDefault();
    let dataDelete = $(this).data('delete');
    // console.log(dataDelete);
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type:'DELETE',
                url:dataDelete,
                data:{
                    _token:"{{ csrf_token() }}"
                },
                success:function(response){
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                    location.reload();
                // console.log(response);
                    
                },
                error:function(err){
                    console.log(err);
                }
            });
        }
    })
});

</script>
@endpush