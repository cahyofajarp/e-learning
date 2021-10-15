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
                Learning Management
            </a>
        </li> 
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                S.Work Management
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
                <a href="{{ route('admin.studentWork.create',[$classroom,$lesson]) }}" class="btn btn-custom-green mb-3">
                    <i class="mdi mdi-plus"></i> Tambah Tugas
                </a>
                <div class="table-responsive">
                    
                    <table class="table  table-hover table-striped" id="myTable" style="width:150%">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th style="width:20%">Title </th>
                                <th>Created At</th>
                                <th>Due Time</th>
                                <th>File Work</th>
                                <th style="width:20%">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no =1; ?>
                            @foreach ($works as $work)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{!! $work->title !!}</td>
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
                                        @if (\Carbon\Carbon::now() > $work->due)
                                            <span class="btn btn-danger px-3" style="border-radius: 100px;font-size:14px"><i class="mdi mdi-clock"></i> Tugas Sudah Selesai</span>
                                        @else 
                                            <span class="btn btn-success px-3" style="border-radius: 100px;font-size:14px"><i class="mdi mdi-check"></i> Tugas Sukses Dibuat</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.studentWork.preview',[$classroom,$lesson,$work]) }}" class="btn btn-info btn-sm  mb-2"><i class="mdi mdi-eye"></i> Preview Tugas</a> <br>
                                        <a href="{{ route('admin.studentWork.edit',[$classroom,$lesson,$work]) }}" class="btn btn-custom-yellow btn-sm mb-2"><i class="mdi mdi-pencil "></i> Edit</a> <br>
                                        <button data-delete="{{ route('admin.studentWork.destroy',[$classroom,$lesson,$work]) }}" id="btn-delete" class="btn btn-danger btn-sm d-block mb-2"><i class="mdi mdi-delete "></i> Delete</button>
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
            window.location.href = '{{ route("admin.studentWork.lesson",$classroom) }}';
        }
    })  

    if(getData == '' || getData != '{{ $lesson->code }}'){ 
        window.location.href = '{{ route("admin.studentWork.lesson",$classroom) }}';
        
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
                    
                    if(response.success == true){
                        Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                    location.reload();
                    }
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