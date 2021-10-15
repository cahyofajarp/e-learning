@extends('app.app')
@push('add-style')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

@endpush
@section('content')

<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="importExcel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="background-color: white !important">
        <div class="modal-header">
            {{-- <img src="{{ asset('images/conference.png') }}" alt="" style="
                width:20%;
                margin:0 auto;
                margin-right:-10px;
            "> --}}
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <form method="post" action="" enctype="multipart/form-data" id="formExcel">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="" class="text-bold"><small class="text-gray">* Contoh Format Excel</small> </label>
                    <div class="table-responsive p-3">
                        <img src="{{ asset('images/student-template.png') }}" alt="">
                    </div>
                </div>
                <div class="download">
                    <span class="text-gray" style="font-size: 13px">Or Dowload Tempelate</span>
                    <a href="" class="btn btn-sm btn-custom-blue" style="border-radius: 100px"><i class="mdi mdi-download"></i> Download Template</a>
                </div>
                <hr>
                 <div class="form-group">
                     <label for="">Import File Excel</label>
                     <span class="d-block mb-1"><small>Note : Check kembali data siswa , agar tidak terjadi duplicate data(Error)</small></span>
                     <input type="file" name="file" class="form-control mb-1">
                     <span class="text-danger error-text file_error"></span>
                 </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="btn-import" style="letter-spacing: 2"><i class="mdi mdi-file-import"></i> IMPORT</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="importExcel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="background-color: white !important">
        <div class="modal-header">
            <img src="{{ asset('images/5836-min.jpg') }}" alt="" style="
            width: 60%;
            margin: 0 auto;
            margin-right: -10px;
        ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <form method="post" action="" enctype="multipart/form-data" id="formDataUpdate">
            @method('PUT')
            @csrf
            <div class="modal-body">
                <input type="hidden" value="" id="data_update">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Name</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="name" class="form-control" id="name" value="">
                        
                            <span class="text-danger error-text name_error"  style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Registration Number</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="registration_number" class="form-control" readonly  value="" id="registration_number">
                            <span class="text-danger error-text registration_number_error"  style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="address" class="form-control" value="" id="address">
                            <span class="text-danger error-text address_error" style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Pilih Kelas</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <select name="classroom_id" id="select2" class="form-control">
                                {{-- <option value="">---</option> --}}
                                @foreach ($classrooms->sortBy(function($q) {
                                    return $q->levelclass->name;
                                }) as $no => $classroom)

                                    <option id="option{{ $no }}" value="{{ $classroom->id }}" 
                                        >{{ $classroom->levelclass->name }} -- {{ $classroom->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text classroom_id_error" style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Avatar</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="file" name="images" class="form-control">
                            <span class="text-danger error-text images_error" style="font-size: 13px"></span>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="btn-update"><i class="mdi mdi-file-import menu-icon"></i> UPDATE</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content p-2" style="background-color: white !important">
        <div class="modal-header">
            <img src="{{ asset('images/5836-min.jpg') }}" alt="" style="
                width: 50%;
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Name</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="name" class="form-control">
                        
                            <span class="text-danger error-text name_error"  style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Registration Number</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="registration_number" class="form-control">
                            <span class="text-danger error-text registration_number_error"  style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="address" class="form-control">
                            <span class="text-danger error-text address_error" style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Pilih Kelas</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <select name="classroom_id" id="select2" class="form-control">
                                <option value="">---</option>
                                @foreach ($classrooms->sortBy(function($q) {
                                    return $q->levelclass->name;
                                }) as $classroom)
                                    <option 
                                    
                                    value="{{ $classroom->id }}">{{ $classroom->levelclass->name }} -- {{ $classroom->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text classroom_id_error" style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Avatar</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="file" name="images" class="form-control">
                            <span class="text-danger error-text images_error" style="font-size: 13px"></span>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-create" style="letter-spacing: 3px">CREATE</button>
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
    <span class="">Loading ...</span>
</div>
<div class="row">
    <div class="col-md-12">
      
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Table</h5>

            </div>
            <div class="card-body">
                <div class="text-header mb-5">
                    <a href="" class="btn btn-custom-green" data-toggle="modal" data-target="#exampleModal"><i class="mdi mdi-plus"></i> Tambah Siswa</a>
                    <a href="" data-toggle="modal" data-target="#importExcel" class="btn btn-custom-blue"><i class="mdi mdi-file-import"></i> Import Excel File</a>
                    <a href="{{ route('admin.student.exportPdf') }}" class="btn btn-custom-red"><i class="mdi mdi-file-export"></i> Download PDF</a>
                </div>
                <div class="table-responsive">
                    
                <table class="table table-striped  table-bordered" id="myTable" style="width: 140%">
                    <thead class="btn-custom-blue">
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th>Alamat</th>
                            <th>Avatar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;  ?>
                        @foreach ($students->sortBy(function($q) {
                            return $q->classroom->levelclass->name;
                        }) as $student)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $student->classroom->levelclass->name }} - {{ $student->classroom->name }}</td>
                                <td style="width: 15%">{{ $student->name }}</td>
                                <td>{{ $student->registration_number }}</td>
                                <td>{{ $student->address }}</td>
                                <td style="width: 15%"><img src="{{ asset('storage/'.$student->images) }}" alt="" style="width: 150px;max-height:150px;object-fit:contain"></td>
                                <td>
                                    <button data-update="{{ route('admin.student.update',$student) }}" 
                                        data-url="{{ route('admin.student.edit',$student) }}" 
                                        id="btn-edit" 
                                        class="mb-2 btn btn-sm btn btn-custom-yellow">
                                        
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </button>

                                    <button data-delete="{{ route('admin.student.destroy',$student) }}" 
                                            id="btn-delete" 
                                            class="mb-2 btn btn-custom-red btn-sm btn-danger" 
                                            style="color:white">
                                            
                                            <i class="mdi mdi-delete"></i> Delete
                                    </button>
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
    $('#select2').select2({
        theme: "bootstrap"
    });
});

    
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


$('#formData').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: "{{ route('admin.student.store') }}",
        beforeSend:function(){
            $('#btn-create').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-create').removeClass("disabled").html("CREATE").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
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
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error').text(val[0]);
            })

            console.log(err);
        }

    })
});

$('#formExcel').submit(function(e) {
    e.preventDefault();
    
    // let form = $('#formData').serialize();
    
    var formData = new FormData(this);

    // console.log(form);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: "{{ route('admin.student.import') }}",
        beforeSend:function(){
            $('#btn-import').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-import').removeClass("disabled").html("IMPORT").attr('disabled',false);
            
        },
        success: function(res){
            if(res.success == true){
                Swal.fire(
                'Success!',
                'Data Siswa Berhasil Diimport!',
                'success'
                )   
                $('#importExcel').modal('hide');
                $('#formExcel').trigger("reset");
                location.reload();
            }else{
                $('#importExcel').modal('hide');
                $('#formExcel').trigger("reset");
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: res.message,
                })
                
                   
            }
            
        },
        error(err){

            if(err){
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

            for(let i= 0; i < res.students.length;i++){
                if(res.students[i].id == res.student.classroom_id){
                    
                    $('#select2').val(res.student.classroom_id);
                    $('#select2').trigger('change');
                }
                

            }
            $('#data_update').val(data_update);
            $('#name').val(res.student.name);
            $('#registration_number').val(res.student.registration_number);
            $('#address').val(res.student.address);
            $('#modalUpdate').modal('toggle');
        }
    })

})

$('#formDataUpdate').submit(function(e) {
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
            if(res.success == true){
                Swal.fire(
                'Success!',
                'You data is successfuly updated!',
                'success'
                )   
                $('#modalUpdate').modal('hide');
                $('#formDataUpdate').trigger("reset");
                location.reload();

            }
        },
        error(err){
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error').text(val[0]);
            })

            console.log(err);
        }

    })
});
</script>
@endpush