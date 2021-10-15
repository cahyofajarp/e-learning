@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

@endpush
@section('content')
<div class="modal" id="addMapel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <label for="">Nama Mata Pelajaran</label>
                    <span class="text-gray d-block mb-1"><small>* Contoh : Matematika, Bahasa Indonesia</small></span>
                    <input type="text" name="name" class="form-control" placeholder="Matematika, Bahasa Indonesia" style="font-size: 14px">
                    <span class="text-danger error-text name_error" style="font-size:13px"></span>
                </div>    
                <div class="form-group">
                    <label for="">Type Mata Pelajaran</label>
                    <span class="text-gray d-block mb-1"><small>* Noted: Type mapel apakah produktif / tidak</small></span>
                    <select name="type" id="" class="form-control" style="font-size:13px">
                        <option value="">---</option>
                        <option value="PRODUKTIF">PRODUKTIF</option>
                        <option value="BUKAN PRODUKTIF" selected>BUKAN PRODUKTIF</option>
                    </select>
                    <span class="text-danger error-text type_error" style="font-size:13px"></span>
                </div>  
                <div class="form-group">
                    <label for="">Code Mata Pelajaran</label>
                    <span class="text-gray d-block mb-1"><small>* Noted : Code ini untuk identifikasi / keamamanan.</small></span>
                    <input value="" id="" type="text" name="code" class="form-control code" placeholder="Enter custom Code" style="font-size: 14px">
                    <span class="d-block text-danger error-text code_error" style="font-size:13px"></span>
                    <span class="d-block " style="font-size:13px">- Or -</span>
                    <button id="" type="button" class="btn btn-custom-blue btn-code mt-1" onclick="getRandomCode()">Get Random Code</button>
                </div> 
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-create" style="letter-spacing: 3px">CREATE</button>
            </div>
        </form>
      </div>
    </div>
</div>


<div class="modal" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <form method="post" action="" id="formDataUpdate" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <input type="hidden" id="data_update" value="">
                <div class="form-group">
                    <label for="">Nama Mata Pelajaran</label>
                    <span class="text-gray d-block mb-1"><small>* Contoh : Matematika, Bahasa Indonesia</small></span>
                    <input id="name" type="text" value="" name="name" class="form-control" placeholder="Matematika, Bahasa Indonesia" style="font-size: 14px">
                    <span class="text-danger error-text name_error_update" style="font-size:13px"></span>
                </div>    
                <div class="form-group">
                    <label for="">Type Mata Pelajaran</label>
                    <span class="text-gray d-block mb-1"><small>* Noted: Type mapel apakah produktif / tidak</small></span>
                    <select name="type" id="type" class="form-control" style="font-size:13px">
                        <option value="">---</option>
                        <option value="PRODUKTIF">PRODUKTIF</option>
                        <option value="BUKAN PRODUKTIF" selected>BUKAN PRODUKTIF</option>
                    </select>
                    <span class="text-danger error-text type_error_update" style="font-size:13px"></span>
                </div>  
                <div class="form-group">
                    <label for="">Code Mata Pelajaran</label>
                    <span class="text-gray d-block mb-1"><small>* Noted : Code ini untuk identifikasi / keamamanan.</small></span>
                    <input value="" id="code" type="text" name="code" class="form-control code" placeholder="Enter custom Code" style="font-size: 14px">
                    <span class="d-block text-danger error-text code_error_update" style="font-size:13px"></span>
                    <span class="d-block " style="font-size:13px">- Or -</span>
                    <button type="button" class="btn btn-custom-blue mt-1 btn-code" onclick="getRandomCode()">Get Random Code</button>
                </div> 
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-update" style="letter-spacing: 3px">UPDATE</button>
            </div>
        </form>
      </div>
    </div>
</div>


<div id="loading" class="d-none" style="

    position: fixed;
        top: 50%;
        right: 50%;
        z-index: 999;
        color: black;
">
    <span class="bg-white">Loading ...</span>
</div>

<nav aria-label="Page breadcrumb">
    <ol class="breadcrumb" style="background: white">
        <li class="breadcrumb-item" aria-current="page">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.home') }}"><i class="mdi mdi-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.lesson.index') }}">
                Classroom
            </a>
        </li>
        <li class="breadcrumb-item" style="color:#6c757d">
               {{ $classroom->levelclass->name }} {{ $classroom->name }}
            
        </li>
        <li class="breadcrumb-item" style="color:black">
            Lesson
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        @if (Session::has('message'))
            <div class="alert alert-warning mb-2">{{ Session::get('message') }}</div>
        @endif 
        @if (Session::has('success'))
            <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="text-header mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex ">
                                <div class="align-items-end">
                                    <a href="" class="btn btn-custom-green"  data-toggle="modal" data-target="#addMapel"><i class="mdi mdi-plus"></i> Tambah Mapel</a>
                            
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <span><small>Pelajaran akan di input otomatis dikelas yang sama (akan ditambahkan jika belum ada pelajarannya)</small></span>
                            <span><small>Tambahkan Mata Pelajaran Di Kelas :</small></span>
                            <form action="{{ route('admin.lesson.addLessonAutomatic',$classroom) }}" method="post">
                                @csrf
                                <div class="input-group">
                                    <select required style="border-radius: none !important" name="add_lesson[]" id="" class="form-control select2 custom-select" multiple>
                                        @foreach ($levelclasses->classrooms()->where('name','like','%'.substr($classroom->name,0,-1).'%')->get() as $item)
                                            @if ($classroom->name != $item->name)
                                            <option value="{{ $item->id }}">{{ $levelclasses->name }} {{ $item->name }}</option>
                                            
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="input-group-append mt-3">
                                      <button type="submit" class="btn btn-custom-purple" type="button"><i class="mdi mdi-plus"></i> Add Automatic </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    
                    <table class="table table-striped" id="myTable">
                        <thead class="btn-custom-green-blue">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Nama Pelajaran</th>
                                <th>Code Pelajaran</th>
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
                                            <span class="badge badge-warning" style="font-size: 14px">{{ $lesson->code }}</span>
                                    </td>
                                    <td>
                                        <button data-update="{{ route('admin.lesson.update',[$classroom,$lesson]) }}" 
                                        data-url="{{ route('admin.lesson.edit',[$classroom,$lesson]) }}" 
                                        id="btn-edit" 
                                        class="mb-2 btn btn-sm btn btn-custom-yellow">
                                        
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </button>

                                    <button data-delete="{{ route('admin.lesson.destroy',[$classroom,$lesson]) }}" 
                                            id="btn-delete" 
                                            class="mb-2 btn btn-custom-red btn-sm btn-danger" 
                                            style="color:white">
                                            
                                            <i class="mdi mdi-delete"></i> Delete
                                    </button>
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
        $('.select2').select2({
            theme: "bootstrap"
        });
    });



function getRandomCode(){
    $.ajax({
        type: 'GET',
        contentType: false,
        processData: false,
        url: "{{ route('admin.lesson.getRandomCode',$classroom) }}",   
        beforeSend:function(){
            $('.btn-code').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('.btn-code').removeClass("disabled").html("Get Random Code").attr('disabled',false);
            
        },
        success:function(res){
            $('.code').val(res.code);
            // $('.code').val(res.code);
        }
    })
}

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


$('#formDataCreate').submit(function(e) {
    let checkIfError = null;
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: "{{ route('admin.lesson.store',$classroom) }}",
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
                $('#exampleModal').modal('hide');
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

$('button#btn-edit').click(function(e) {
    let url = $(this).data('url');
    let data_update = $(this).data('update');
    
    e.preventDefault();
    
    $.ajax({
        url: url,
        type:'GET',
        dataType:'json',     
        beforeSend:function(){
            $('#loading').removeClass('d-none');
        },
        complete:function(){
            $('#loading').addClass('d-none');
        },

        success:function(res){

            $('#data_update').val(data_update);
            $('#name').val(res.lesson.name);
            $('#type').val(res.lesson.type);
            $('.code').val(res.lesson.code);
            $('#modalUpdate').modal('toggle');
        }
    })

})

$('#formDataUpdate').submit(function(e) {
        let checkIfError = null;
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#data_update').val();

        $.ajax({
            type: 'POST',
            data : formData,
            contentType: false,
            processData: false,
            url: url,
            beforeSend:function(){
                $('#btn-update').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
                $(document).find('span.error-text').text('');
            },    
            complete: function(){
                $('#btn-update').removeClass("disabled").html("UPDATE").attr('disabled',false);
                
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
                    $('#modalLevelclass').modal('hide');
                    $('form').trigger("reset");
                    location.reload();

                }
            },
            error(err){
                
                checkIfError = true;
                if(checkIfError == true){
                    $.each(err.responseJSON.errors,function(prefix,val) {
                        $('.'+prefix+'_error_update').text(val[0]);
                    })
                }

                console.log(err);
            }

        })
    });

</script>
@endpush