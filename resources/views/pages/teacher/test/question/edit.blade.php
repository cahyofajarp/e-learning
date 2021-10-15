@extends('app.app')
@push('add-style')
    
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">
<style>
   .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
@endpush
@section('content')


<nav aria-label="Page breadcrumb">
    <ol class="breadcrumb" style="background: white">
        <li class="breadcrumb-item" aria-current="page">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.home') }}"><i class="mdi mdi-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                Test Management
            </a>
        </li> 
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                {{ $classroom->levelclass->name }} {{ $classroom->name }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.test.testCreate',[$classroom,$lesson]) }}">
                {{ $lesson->name }}
            </a>
        </li>
        <li class="breadcrumb-item" style="color:black">
            Question
        </li> 
        <li class="breadcrumb-item" style="color:black">
            {{ $no }}
        </li> 
        <li class="breadcrumb-item" style="color:black">
            Edit
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('teacher.lesson.test.question.update',[$classroom,$lesson,$test,$question,$no]) }}" method="post" enctype="multipart/form-data" id="formData">
                @csrf
                @method('put')
                <div class="form-group">
                    <div class="float-right px-3 py-2" style="
                        background: #8bc34a;
                        box-shadow:0 30px 60px -12px rgb(50 50 93 / 25%), 0 18px 36px -18px rgb(0 0 0 / 30%), 0 -12px 36px -8px rgb(0 0 0 / 3%);
                        position: relative;
                        color: white;
                        top: -44px;
                        border-radius:100px;
                    ">No. {{ $no }}</div>
                    <label for="">*Pertanyaan</label>
                    <small class="d-block mb-2">Noted : Tulis pertanyaan.</small>
                    <textarea class="editor1" name="question" >{{ $question->ask }}</textarea>
                    <span class="text-danger error-text question_error" style="font-size:13px"></span>
                </div>
                
                <div class="form-group">
                    <label for="">*Option1</label>
                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                    <input type="text" value="{{ old('option5',$question->option1) }}" name="option1" class="form-control">
                    <span class="text-danger error-text option1_error" style="font-size:13px"></span>
                </div>

                <div class="form-group">
                    <label for="">*Option2</label>
                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                    <input type="text" value="{{ old('option5',$question->option2) }}" name="option2" class="form-control">
                    <span class="text-danger error-text option2_error" style="font-size:13px"></span>
                </div>

                <div class="form-group">
                    <label for="">*Option3</label>
                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                    <input type="text" value="{{ old('option5',$question->option3) }}" name="option3" class="form-control">
                    <span class="text-danger error-text option3_error" style="font-size:13px"></span>
                </div>

                <div class="form-group">
                    <label for="">*Option4</label>
                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                    <input type="text" value="{{ old('option5',$question->option4) }}" name="option4" class="form-control">
                    <span class="text-danger error-text option4_error" style="font-size:13px"></span>
                </div>
                <div class="form-group">
                    <label for="">*Option5</label>
                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                    <input type="text" value="{{ old('option5',$question->option5) }}" name="option5" class="form-control">
                    <span class="text-danger error-text option5_error" style="font-size:13px"></span>
                </div>
                <div class="form-group">
                    <label for="">*Score</label>
                    <small class="d-block mb-2">Noted: Buat score untuk setiap soal.</small>
                    <input type="text" value="{{ old('score',$question->score) }}" name="score" class="form-control scoreSystem" readonly>
                    <span class="text-danger error-text score_error" style="font-size:13px"></span>
                </div>
                <div class="form-group">
                    <label for="">* Jawaban Benar</label>
                    <small class="d-block mb-2">Noted: Silahkan Jawaban Benar Untuk Penilaian.</small>
                    <input type="text" name="answer" value="{{ old('answer',$question->answer) }}" class="form-control">
                    <span class="text-danger error-text answer_error" style="font-size:13px"></span>
                </div>

                <button class="btn btn-block btn-custom-green" id="btn-update">UPDATE</button>
                </form>
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
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>

ClassicEditor
    .create( document.querySelector( '.editor1' ) )
    .catch( error => {
        console.error( error );
    } );



$('#formData').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url:$(this).attr('action'),
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
                'You data is successfuly updated!',
                'success'
                )   
                window.location.href = '{{ route("teacher.lesson.test.question.create",[$classroom,$lesson,$test]) }}';
                console.log(res);
            }
        },
        error(err){
            $.each(err.responseJSON.errors,function(prefix,val) {
                // $('[class*="'+prefix+'_error"]').text(val[0]);
                
                $('.'+prefix+'_error').text(val[0]);
            })

            if(err){
                Swal.fire(
                'Error!',
                'Check kembali ada data yg kosong / tidak sesuai!',
                'error'
                )   
            }
        }

    })
});
</script>
@endpush