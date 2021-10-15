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
                <form id="formData" action="{{ route('admin.studentWork.update',[$classroom,$lesson,$work]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">  
                    <small class="text-gray mb-2 d-block"><b>Noted : Tambahkan guru ini hanya tersedia untuk admin.</b></small>
                    <hr>
                    <label for="">* Nama Guru</label><br>
                    <label for=""><small class="text-gray">Note : Masukan nama guru wajib!</small></label>
                    <select name="teacher_id" id="" class="form-control select2">
                    <option value="">---</option>
                    @foreach ($lessonTeacher->teachers as $teacher)
                        <option value="{{ (int)$teacher->id }}" 
                            @if ($teacher->id == $work->teacher_id) selected @endif>{{ $teacher->name }}</option>
                       
                    @endforeach
                    </select>    
                    <span class="text-danger error-text teacher_id_error" style="font-size:13px"></span>
                </div>
                
                <div class="form-group">
                    <label for="">*Judul (Subject)</label>  
                    <small class="d-block mb-2">Noted : Tulis Title</small>
                    <input type="text" multiple class="form-control" name="title" placeholder="Tugas Pemograman Web" value="{{ old('title',$work->title) }}">
                    <span class="text-danger error-text title_error" style="font-size:13px"></span>
                </div>
                <div class="form-group">
                    <label for="">* Description</label>
                    <small class="d-block mb-2">Noted : Keterangan.</small>
                    <textarea class="editor1" name="description" value="">{{ old('description',$work->description) }}</textarea>
                    <span class="text-danger error-text description_error" style="font-size:13px"></span>
                </div>

                <div class="form-group">
                    <label for="">* Due (Batas Waktu)</label>  
                    <small class="d-block mb-2">Noted : Batas Waktu.</small>
                    <input type="datetime-local" multiple class="form-control" name="due" value="{{ old('due',date('Y-m-d\TH:i', strtotime($work->due))) }}">
                    <span class="text-danger error-text due_error" style="font-size:13px"></span>
                </div>

                <div class="form-group">
                    <label for="">*File (PDF) </label>  
                    <small class="d-block mb-2">Noted : Taruh File</small>
                    <input type="file" class="form-control" name="file" id="imgInp" value="">
                    <span class="text-danger error-text file.0_error" style="font-size:13px"></span>
                </div>
                
            </div>
        </div>

        <div class="card" id="card">
            <div class="card-body">
                <small class="d-block">Preview : </small>
                {{-- <embed id="blah" width="50%" name="plugin" src="" type="application/pdf" style="height:300px">
            </div> --}}
            
                <div class="">
                    <div class="pdf mt-5">
                        @foreach ($work->fileworks as $file)
                            <div class="d-inline file-edit " style="position: relative;">
                                <div class="btn-custom-green btn remove-button" data-id="{{ $file->id }}" style="
                                position: relative;
                                z-index: 2;
                                right: -10px;
                                bottom: 300px;
                                cursor: pointer;
                                border-radius: 100px;">
                                    <i class="mdi mdi-close"></i>
                                </div>
                                <input type="hidden" name="file_id[]" value="{{ $file->id }}" id="file-id" >
                                <embed id="" width="28%" name="plugin" src="{{ Storage::url($file->file) }}" type="application/pdf" style="    border: 10px solid #1c2d41;
                                    height: 300px;">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        
        <button id="btn-update" type="submit" class="btn btn-block btn-custom-green">SAVE</button>
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
$(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap"
        });
    });

ClassicEditor
    .create( document.querySelector( '.editor1' ) )
    .catch( error => {
        console.error( error );
    } );


$(document).on('click','.remove-button',function(){
    let data = $(this).data('id');
    $('#imgInp').val('');
    $(this).closest('.file-edit').remove();
});


// function resetFile(val) {
//     // $('#imgInp').val(val[0]);
//     var files = document.getElementById("imgInp").files;

// for (var i = 0; i < files.length; i++)
// {
//     console.log(document.getElementById("imgInp").value);
// }

// }

function readURL(input,placeToInsertImagePreview) {
    if (input.files){
        for(i = 0; i < input.files.length;i++){
            var reader = new FileReader();

            reader.onload = function (e) {
                
                var viewHtml = '';
                viewHtml += `<div  class="d-inline file-edit " style="position: relative;">    
                    <div class="btn-custom-green btn remove-button" style="position: relative;
                                z-index: 2;
                                right: -10px;
                                bottom: 300px;
                                cursor: pointer;
                                border-radius: 100px;">
                                    <i class="mdi mdi-close"></i>
                                </div>
                                    <embed width="28%" name="plugin" src="`+event.target.result+`" type="application/pdf" style="    border: 10px solid #1c2d41;
                                    height: 300px;">
                            </div>`;
                //  $($.parseHTML('<embed>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                $('.pdf').append(viewHtml);
            }


            reader.readAsDataURL(input.files[i]);
        }
        
            resetFile(input.files);
    }
}

$("#imgInp").change(function(){
    readURL(this,'div.pdf');
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

$('#formData').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: $(this).attr('action'),
        beforeSend:function(){
            $('#btn-update').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-update').removeClass("disabled").html("CREATE").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
            if(res){
                Swal.fire(
                'Success!',
                'You data is successfuly created!',
                'success'
                )   
                
                window.location.href = '{{ route("admin.studentWork.index",[$classroom,$lesson]) }}';
            }
        },
        error(err){
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