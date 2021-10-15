@extends('app.app')
@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

@endpush
@section('content')
<style>
 .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
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
        <li class="breadcrumb-item" style="color:black">
            Create
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="formData" action="{{ route('teacher.work.student.store',[$classroom,$lesson]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="">* Tipe Pekerjaan </label>  
                    <small class="d-block mb-2">Noted : Pilih salah satu tugas atau materi</small>
                    <select name="type" class="form-control" style="border:2px dotted #707170" id="changeType">
                        
                        <option value="TUGAS">TUGAS</option>
                        <option value="MATERI">MATERI</option>
                    </select>
                    <span class="text-danger error-text type_error" style="font-size:13px"></span>
                </div>
                <div class="form-group change_type">
                    <label for="">* Standar Nilai (KKM)</label>  
                    <small class="d-block mb-2">Noted : Nilai standard / KKM </small>
                    <input type="number" class="form-control" name="standard" placeholder="Nilai KKM">
                    <span class="text-danger error-text standard_error" style="font-size:13px"></span>
                </div>
                
                <div class="form-group">
                    <label for="">*Judul (Subject)</label>  
                    <small class="d-block mb-2">Noted : Tulis Title</small>
                    <input type="text" multiple class="form-control" name="title" placeholder="Tugas Pemograman Web">
                    <span class="text-danger error-text title_error" style="font-size:13px"></span>
                </div>
                <div class="form-group">
                    <label for="">* Description</label>
                    <small class="d-block mb-2">Noted : Keterangan.</small>
                    <textarea id="des" class="editor1" name="description"></textarea>
                    <span class="text-danger error-text description_error" style="font-size:13px"></span>
                </div>

                <div class="form-group change_type">
                    <label for="">* Due (Batas Waktu)</label>  
                    <small class="d-block mb-2">Noted : Batas Waktu.</small>
                    <input type="datetime-local" multiple class="form-control" name="due">
                    <span class="text-danger error-text due_error" style="font-size:13px"></span>
                </div>

                <div class="form-group">
                    <label for="">*File (PDF) </label>  
                    <small class="d-block mb-2">Noted : Taruh File</small>
                    <input type="file" multiple class="form-control" name="file[]" id="imgInp">
                    <span class="text-danger error-text file.0_error" style="font-size:13px"></span>
                </div>
                
            </div>
        </div>

        <div class="card" style="display: none" id="card">
            <div class="card-body">
                <small class="d-block">Preview : </small>
                {{-- <embed id="blah" width="50%" name="plugin" src="" type="application/pdf" style="height:300px">
            </div> --}}
                <div class="pdf"></div>
            </div>
        </div>

        
        <button id="btn-create" type="submit" class="btn btn-block btn-custom-green">CREATE</button>
        </form>
    </div>
</div>


@endsection
@push('add-script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>

<script>
var editors;
ClassicEditor
    .create( document.querySelector( '.editor1' ) )
    .then(editor => {
        editors = editor;
    })
    .catch( error => {
        console.error( error );
    } );

function readURL(input,placeToInsertImagePreview) {
    if (input.files){
        for(i = 0; i < input.files.length;i++){
            var reader = new FileReader();

            reader.onload = function (e) {
                
                var viewHtml = '';
                viewHtml += `<div class="d-inline ml-4">
                                    <embed id="blah" width="30%" name="plugin" src="`+event.target.result+`" type="application/pdf" style="height:300px">
                            </div>`;
                //  $($.parseHTML('<embed>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                $('.pdf').append(viewHtml);

                console.log(e);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }
}

$('#changeType').on('change',function() {
    var form = document.getElementById("formData");
    
    var inputs = $('#formData').find('input');  
    
    editors.setData('');
    inputs.val('');

    $('.pdf').append('');  
    $('#card').hide();
    
    if($(this).val() == 'MATERI'){
        
        $('.change_type').hide();
    }
    else{
        $('.change_type').show();
    }
})

$("#imgInp").change(function(){
    readURL(this,'div.pdf');
    $('.pdf').html('');
    $('#card').show();
});

$('#formData').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    formData.append('_token', '{{ csrf_token() }}');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
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
            if(res.success == true){
                Swal.fire(
                'Success!',
                'You data is successfuly created!',
                'success'
                )   
                
                // $('.count').text(res.data.length);
                // console.log(res);
                // window.location.href = '{{ route("teacher.work.student.work",[$classroom,$lesson]) }}';
            }
            
        },
        error(err){
            Swal.fire(
                'Error!',
                'Check again your data!',
                'error'
            )    
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error').text(val[0]);
                
                $('[class*="'+prefix+'_error"]').text(val[0]);
            })

            console.log(err);
        }

    })
});
</script>
@endpush