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
                        <img src="{{ asset('images/teacher-template.png') }}" alt="">
                    </div>
                </div>
                <div class="download">
                    <span class="text-gray" style="font-size: 13px">Or Dowload Tempelate</span>
                    <a href="" class="btn btn-sm btn-custom-blue" style="border-radius: 100px"><i class="mdi mdi-download"></i> Download Template</a>
                </div>
                <hr>
                 <div class="form-group">
                     <label for="">Import File Excel</label>
                     <span class="d-block mb-1"><small>Note : Check kembali data guru , agar tidak terjadi duplicate data(Error)</small></span>
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
                <input type="hidden" value="" id="data_update">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Name</label>
                                <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                                <input type="text" name="name" class="form-control" value="" id="name">
                            
                                <span class="text-danger error-text name_error"  style="font-size: 13px"></span>
                            </div>
                            <div class="form-group">
                                <label for="">NIP</label>
                                <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                                <input type="text" name="NIP" class="form-control" value="" id="NIP">
                                <span class="text-danger error-text NIP_error"  style="font-size: 13px"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Pendidikan Terakhir</label>
                                <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                                <input type="text" name="last_education" class="form-control" value="" id="last_education">
                                <span class="text-danger error-text last_education_error"  style="font-size: 13px"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Lahir</label>
                                <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                                <input type="date" name="tanggal_lahir" class="form-control" value="" id="tanggal_lahir">
                                <span class="text-danger error-text tanggal_lahir_error"  style="font-size: 13px"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                                <input type="text" name="alamat" class="form-control" value="" id="alamat">
                                <span class="text-danger error-text alamat_error" style="font-size: 13px"></span>
                            </div>
                            <div class="form-group">
                                <label for="">No Handphone</label>
                                <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                                <input type="number" name="no_hp" class="form-control" value="" id="no_hp">
                                <span class="text-danger error-text no_hp_error" style="font-size: 13px"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Avatar</label>
                                <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                                <input type="file" name="avatar" class="form-control" value="" id="avatar">
                                <span class="text-danger error-text avatar_error" style="font-size: 13px"></span>
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
            <img src="{{ asset('images/8600-min.jpg') }}" alt="" style="
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
                            <label for="">NIP</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="NIP" class="form-control">
                            <span class="text-danger error-text NIP_error"  style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Pendidikan Terakhir</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="last_education" class="form-control">
                            <span class="text-danger error-text last_education_error"  style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Lahir</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="date" name="tanggal_lahir" class="form-control">
                            <span class="text-danger error-text tanggal_lahir_error"  style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="text" name="alamat" class="form-control">
                            <span class="text-danger error-text alamat_error" style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">No Handphone</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="number" name="no_hp" class="form-control">
                            <span class="text-danger error-text no_hp_error" style="font-size: 13px"></span>
                        </div>
                        <div class="form-group">
                            <label for="">Avatar</label>
                            <small class="d-block mb-1">*Isi dengan benar Wajib!</small>
                            <input type="file" name="avatar" class="form-control">
                            <span class="text-danger error-text avatar_error" style="font-size: 13px"></span>
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
                    <a href="" class="btn btn-custom-green" data-toggle="modal" data-target="#exampleModal"><i class="mdi mdi-plus"></i> Tambah Guru</a>
                    <a href="" data-toggle="modal" data-target="#importExcel" class="btn btn-custom-blue"><i class="mdi mdi-file-import"></i> Import Excel File</a>
                    <a href="" class="btn btn-custom-red"><i class="mdi mdi-file-export"></i> Download PDF</a>
                </div>
                <div class="table-responsive">
                    
                <table class="table table-striped table-bordered" id="myTable" style="width: 140%">
                    <thead class="btn-custom-green-blue">
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>NIP</th>
                            <th>Tanggal Lahir</th>
                            <th>Pendidikan Terakhir</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Avatar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;  ?>
                        @foreach ($teachers as $teacher)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->NIP }}</td>
                                <td>{{ $teacher->tanggal_lahir }}</td>
                                <td>{{ $teacher->last_education }}</td>
                                <td>{{ $teacher->alamat }}</td>
                                <td>{{ $teacher->no_hp }}</td>
                                <td style="width: 15%">
                                @if ($teacher->avatar)
                                    <img src="{{  asset('storage/'.$teacher->avatar) }}" alt="" style="width: 150px;max-height:150px;object-fit:contain">
                                @else 
                                    No Photo
                                @endif
                                </td>
                                <td>
                                    <button data-update="{{ route('admin.teacher.update',$teacher) }}" 
                                        data-url="{{ route('admin.teacher.edit',$teacher) }}" 
                                        id="btn-edit" 
                                        class="mb-2 btn btn-sm btn btn-custom-yellow">
                                        
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </button>

                                    <button data-delete="{{ route('admin.teacher.destroy',$teacher) }}" 
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
        url: "{{ route('admin.teacher.store') }}",
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
        url: "{{ route('admin.teacher.importExcel') }}",
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
                'Data Guru Berhasil Diimport!',
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
            
            $('#modalUpdate').modal('toggle');
            $('#data_update').val(data_update);
            $('#name').val(res.teacher.name);
            $('#last_education').val(res.teacher.last_education);
            $('#alamat').val(res.teacher.alamat);
            $('#NIP').val(res.teacher.NIP);
            $('#tanggal_lahir').val(res.teacher.tanggal_lahir);
            $('#no_hp').val(res.teacher.no_hp);
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