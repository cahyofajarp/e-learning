@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')


<div class="modal fade" id="modalCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content p-2" style="background-color: white !important">
        <div class="modal-header">
            <img src="{{ asset('images/6663-min.jpg') }}" alt="" style="  
            width: 70%;
            margin: 0 auto;
            margin-right: -10px;
        ">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <form method="post" action="" id="formData" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="data-redirect" value="">
                    <label for="">Code Kelas</label>
                    <span class="text-gray d-block mb-1"><small>* Noted: Silahkan masukan code kelas</small></span>
                    <input type="text" name="code" class="form-control">
                    <span class="text-danger error-text code_error" style="font-size:13px"></span>
                </div> 
            </div>
            <input type="hidden" name="lesson" id="lesson" value="">

            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-create-test" style="letter-spacing: 3px">CHECK CODE</button>
            </div>
        </form>
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
        <li class="breadcrumb-item" style="color:black">
            {{ $classroom->levelclass->name }} {{ $classroom->name }}
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-striped" id="myTable" style="width: 120%">
                        <thead class="btn-custom-green-blue">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Nama Pelajaran</th>
                                <th>Jumlah Tugas ( <small>Aktif</small> )</th>
                                <th>Jumlah Tugas( <small>Selesai</small> )</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($lessons->sortByDesc('type') as $lesson)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>
                                        <span class="badge {{ $lesson->type == 'PRODUKTIF' ? 'btn-custom-red' : 'btn-custom-purple' }}">
                                        {{ $lesson->type }}</span>
                                    </td>

                                    <td>{{ $lesson->name }}</td>
                                   
                                    <td>
                                        <span class="btn btn-success"> 
                                            <small>
                                            {{ $workCountLesson->works()->where('due','>',\Carbon\Carbon::now())->where('lesson_id',$lesson->id)->get()->count() }} Tugas | <i class="mdi mdi-check"></i> Active</small></span> 
                                    </td>
                                    <td>
                                        
                                        <span class="btn btn-warning"> 
                                            <small>
                                            {{ $workCountLesson->works()->where('due','<',\Carbon\Carbon::now())->where('lesson_id',$lesson->id)->get()->count() }} Tugas | <i class="mdi mdi-check"></i>Done</small></span> 
                                    </td>
                                    <td>
                                        <button data-lesson="{{ $lesson->code }}" data-redirect="{{ route('teacher.work.student.work',[$classroom,$lesson]) }}" data-check="{{ route('teacher.work.student.checkCode',[$classroom,$lesson]) }}" 
                                                class="btn btn-info" 
                                                id="btn-test"> <i class="mdi mdi-clipboard"></i> <small>Tambah Tugas / Materi</small></button>
                                        {{-- <button class="btn btn-custom-green"> <i class="mdi mdi-plus"></i> Tambah Tugas</button> --}}
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

<script>
// deleteData();
// function  deleteData() {
//     localStorage.removeItem('data');
// }
$('button#btn-test').click(function() {
    $('#modalCode').modal('show');
    let data = $(this).data('check');
    let lesson = $(this).data('lesson');
    let redirect = $(this).data('redirect');
    
    $('form').trigger("reset");
    $('#formData').attr('action',data);

    $('#data-redirect').val(redirect);

    $('#lesson').attr('value',lesson);
    
    
});
// function testMiddleware(){
//     let dataLocalStorage = localStorage.setItem('data',$('#lesson').attr('value'));
    
//     let getData = localStorage.getItem('data');

//     $.get('/admin/test/{{ $classroom->slug }}/classroom/lesson/{{ $lesson->slug }}/testcode/'+getData,function(data){
//         console.log(data);
//     })    
// }

$('#formData').submit(function(e) {
    let checkIfError = null;
    e.preventDefault();
    var formData = new FormData(this);

    let url = $(this).attr('action')
    let redirect = $('#data-redirect').val();

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: url,
        beforeSend:function(){
            $('#btn-create-test').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-create-test').removeClass("disabled").html("CHECK CODE").attr('disabled',false);
            
        },
        success: function(res){

            if(res.success == true){     
                Swal.fire(
                'Success!',
                'Code berhasil,Silahkan buat test sekarang :D',
                'success'
                ) 
    
                $('#modalCode').modal('hide');
                $('form').trigger("reset");
                
                // testMiddleware();

                setTimeout(function() {
                    window.location.href = redirect;
                },500)
            }
            else{ 
                
                Swal.fire(
                'Error!',
                'Oops, Your code is false :( , Check on lesson page.',
                'error'
                ) 
                $('.code_error').text('Opps, Your code is false :( , Check on lesson page.');
            }
        },
        error(err){
            console.log(err);
            checkIfError = true;
            if(checkIfError == true){
                $.each(err.responseJSON.errors,function(prefix,val) {
                    $('.'+prefix+'_error').text(val[0]);
                })
            }

        }

    })
    
});
</script>
@endpush