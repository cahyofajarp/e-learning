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
                <form id="formData" action="{{ route('admin.studentWork.store',[$classroom,$lesson]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">  
                    <small class="text-gray mb-2 d-block"><b>Noted : Tambahkan guru ini hanya tersedia untuk admin.</b></small>
                    <hr>
                    <label for="">* Nama Guru</label><br>
                    <label for=""><small class="text-gray">Note : Masukan nama guru wajib!</small></label>
                    <select name="teacher_id" id="" class="form-control select2">
                    <option value="">---</option>
                    @foreach ($lessonTeacher->teachers as $teacher)
                            <option value="{{ (int)$teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                    </select>    
                    <span class="text-danger error-text teacher_id_error" style="font-size:13px"></span>
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
                    <textarea class="editor1" name="description" ></textarea>
                    <span class="text-danger error-text description_error" style="font-size:13px"></span>
                </div>

                <div class="form-group">
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

ClassicEditor
    .create( document.querySelector( '.editor1' ) )
    .catch( error => {
        console.error( error );
    } );

function readURL(input,placeToInsertImagePreview) {
    if (input.files){
        for(i = 0; i < input.files.length;i++){
            var reader = new FileReader();

            reader.onload = function (e) {
                
                var viewHtml = '';
                viewHtml += `<div class="p-3 d-inline">
                                    <embed id="blah" width="40%" name="plugin" src="`+event.target.result+`" type="application/pdf" style="height:300px">
                            </div>`;
                //  $($.parseHTML('<embed>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                $('.pdf').append(viewHtml);

                console.log(e);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }
}

$("#imgInp").change(function(){
    readURL(this,'div.pdf');
    $('.pdf').html('');
    $('#card').show();
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