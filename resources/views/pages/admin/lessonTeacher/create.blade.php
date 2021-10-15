@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')

<div class="modal" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content p-2" style="background-color: white !important">
        <div class="modal-header">
            <img src="{{ asset('images/Student guy studying on internet-min.jpg') }}" alt="" style="  
            width: 50%;
            margin: 0 auto;
            margin-right: -10px;
        ">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <form method="post" action="" id="formDataCreate" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Teacher</label>
                    <small class="d-block text-gray mb-2">Noted : Tambahkan guru sekarang! jangan sampai tidak ya friend! </small>
                    <select name="teacher_id" id="" class="form-control select2">
                        <option value="">---</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    
                    <span class="text-danger error-text teacher_id_error" style="font-size:13px"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-create" style="letter-spacing: 3px">CREATE</button>
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
        <li class="breadcrumb-item" style="color:#6c757d">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.lessonTeacher.index') }}">
                Lesson Teacher
            </a>
        </li>
        <li class="breadcrumb-item" style="color:#6c757d">
          {{ $classroom->levelclass->name }}  {{ $classroom->name }}
        </li>

        <li class="breadcrumb-item" style="color:black">
            Create
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped" id="myTable">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th>Nama Pelajaran</th>
                                <th>Status</th>
                                <th>Nama Guru</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lessons as $lesson)
                                <tr>
                                    <td></td>
                                    <td>{{ $lesson->name }}</td>
                                   <td>
                                       @if ($lesson->id == $lesson->teachers()->pluck('lesson_id')->first())
                                           <span class="badge badge-success"><i class="mdi mdi-check-circle"></i> Sudah ada guru</span>
                                           @else 
                                           <span class="badge badge-danger"><i class="mdi mdi-close-circle"></i> Belum ada guru</span>
                                       
                                        @endif
                                    </td>
                                    <td>
                                        {{-- <button class="btn btn-custom-green btn-sm">Lihat Nama Guru</button> --}}

                                        {{ $lesson->teachers()->pluck('name')->first() }}
                                    </td>
                                    <td style="width: 25%">
                                        <button class="btn btn-info btn-sm" id="btn-create" data-create="{{ route('admin.lessonTeacher.store',[$classroom,$lesson]) }}">
                                           <i class="mdi mdi-plus"></i> Tambah / Ubah Guru! 
                                        </button>
                                    </td>
                                </tr>
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

    $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap"
        });
    });

</script>
<script>
$('button#btn-create').click(function(){
    let url = $(this).data('create');

    $('#modalCreate').modal('show');
    
    $('#formDataCreate').attr('action',url);
})



$('#formDataCreate').submit(function(e) {
    let checkIfError = null;
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: $(this).attr('action'),
        beforeSend:function(){
            $('#btn-create').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-create').removeClass("disabled").html("CREATE").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
            checkIfError = null;

            if(res){
                Swal.fire(
                'Success!',
                'You data is successfuly created!',
                'success'
                )   
                $('#modalCreate').modal('hide');
                $('form').trigger("reset");
                location.reload();
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