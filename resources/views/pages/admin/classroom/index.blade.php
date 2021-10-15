@extends('app.app')
@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@endpush
@section('content')


<div class="modal" id="modalLevelclass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="background-color: white !important">
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
        <form method="post" action="" enctype="multipart/form-data" id="formDataUpdate" data-url="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <input type="hidden" value="" id="data-url">
                <div class="form-group">
                    <label for="">* Name</label> 
                    <small class="d-block mb-4 text-gray">Noted : Silahkan isi wajib angka ,contoh : 10 (Maksud nya adalah kelas 10) </small>
                            
                    <input type="text" name="name" class="form-control" id="name" value="">
                     <span class="text-danger error-text name_error" style="font-size:13px"></span>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-update" style="letter-spacing: 3px">UPDATE</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <form method="post" action="" id="formDataCreate" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Nama Kelas</label>
                    <span class="text-gray d-block mb-1"><small>* Contoh : Rekaya Perangkat Lunak</small></span>
                    <input type="text" name="name" class="form-control">
                    <span class="text-danger error-text name_error" style="font-size:13px"></span>
                </div> 
                <div class="form-group">
                    <label for="">Sub Kelas</label>
                    <span class="text-gray d-block mb-1"><small>* Contoh : 1 (Sama dengan Rekayasa Perangkat Lunak 1)</small></span>
                    <input type="number" name="sub_class" class="form-control">
                    <span class="text-danger error-text sub_class_error" style="font-size:13px"></span>
                </div>
                <div class="form-group">
                    <label for="">Pilih LevelClass</label>
                    <span class="text-gray d-block mb-1"><small>* Pilih levelclass Contoh : 10 (Sama dengan 10 Rekayasa Perangkat Lunak 1)</small></span>
                    <select name="levelclass_id" class="form-control" id="select2">
                        <option value="">---</option>
                        @foreach ($levelclasses as $levelclass)
                            <option value="{{ $levelclass->id }}">{{ $levelclass->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text levelclass_id_error" style="font-size:13px"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-create" style="letter-spacing: 3px">CREATE</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="text-header mb-5">
                    <a href="" class="btn btn-custom-green"  data-toggle="modal" data-target="#exampleModal"><i class="mdi mdi-plus"></i> Tambah Level Class</a>
                </div>
                <div class="table-responsive">
                    
                <table class="table table-hover table-striped" id="myTable">
                    <thead class="btn-custom-blue text-white">
                        <tr>
                            <th  style="width: 3%">No</th>
                            <th>Level Class</th>
                            <th>Nama Jurusan + Kelas</th>
                            <th style="width: 20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;  ?>
                        @foreach ($classrooms->sortBy('name') as $classroom)
                            <tr>    
                                <td class="text-center">{{ $no }}</td>
                                <td>{{ $classroom->levelclass->name }}</td>
                                <td>{{ $classroom->name }}</td>
                                <td>
                                    <button data-update="{{ route('admin.classroom.update',$classroom) }}" data-url="{{ route('admin.classroom.edit',$classroom) }}" id="btn-edit" href="" class="btn btn-sm btn btn-custom-yellow">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </button>
                                    <button data-delete="{{ route('admin.classroom.destroy',$classroom) }}" id="btn-delete" class="btn btn-custom-red btn-sm btn-danger" style="color:white">
                                        <i class="mdi mdi-pencil"></i> Delete
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

$('#formDataCreate').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: "{{ route('admin.classroom.store') }}",
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
$('#formDataUpdate').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var url = $('#data-url').val();

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
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error').text(val[0]);
            })

            console.log(err);
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
    // alert('wefef');
    $.ajax({
        url: url,
        type:'GET',
        dataType:'json',  
        success:function(res){
            $('#name').val(res.name);
            $('#data-url').val(data_update);
            $('#modalLevelclass').modal('toggle');
        }
    })

})

</script>
@endpush