@extends('app.app')
@push('add-style')
    
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">
<style>
     .ck-editor__editable_inline {
        min-height: 200px;
    }
.float-button-question{
    position: fixed;
    right: 71px;
    bottom: 93px;
}
.float-button-question .question-plus{
    background: #f44336;
    width: 50px;
    height: 50px;
    color: white;
    border-radius: 100px;
    cursor: pointer;
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.float-button-question .question-plus:hover {
    -webkit-transform: scale3d(1.2,1.2,1.2);
    transform: scale3d(1.2,1.2,1.2);
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
                Learning Management
            </a>
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
            QuestionCreate
        </li>

    </ol>
</nav>

<form action="{{ route('teacher.lesson.test.question.store',[$classroom,$lesson,$test]) }}" method="POST" id="formData">
    @csrf
    <div>
        <div class="append-question" > 
            <div class="row collapse show" id="collapseExample">
                <div class="col-md-10"> 
                    <div class="card">
                        <div class="card-body">
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="float-right px-3 py-2" style="
                                            background: #8bc34a;
                                            box-shadow:0 30px 60px -12px rgb(50 50 93 / 25%), 0 18px 36px -18px rgb(0 0 0 / 30%), 0 -12px 36px -8px rgb(0 0 0 / 3%);
                                            position: relative;
                                            color: white;
                                            top: -44px;
                                            border-radius:100px;
                                        ">No. 1</div>
                                    <label for="">*Pertanyaan</label>
                                    <small class="d-block mb-2">Noted : Tulis pertanyaan.</small>
                                    <textarea class="editor1" name="question[]" ></textarea>
                                    <span class="text-danger error-text question.0_error" style="font-size:13px"></span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="">*Option1</label>
                                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                                    <input type="text" name="option1[]" class="form-control">
                                    <span class="text-danger error-text option1.0_error" style="font-size:13px"></span>
                                </div>
    
                                <div class="form-group">
                                    <label for="">*Option2</label>
                                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                                    <input type="text" name="option2[]" class="form-control">
                                    <span class="text-danger error-text option2.0_error" style="font-size:13px"></span>
                                </div>
    
                                <div class="form-group">
                                    <label for="">*Option3</label>
                                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                                    <input type="text" name="option3[]" class="form-control">
                                    <span class="text-danger error-text option3.0_error" style="font-size:13px"></span>
                                </div>
    
                                <div class="form-group">
                                    <label for="">*Option4</label>
                                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                                    <input type="text" name="option4[]" class="form-control">
                                    <span class="text-danger error-text option4.0_error" style="font-size:13px"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">*Option5</label>
                                    <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>
                                    <input type="text" name="option5[]" class="form-control">
                                    <span class="text-danger error-text option5.0_error" style="font-size:13px"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">*Score</label>
                                    <small class="d-block mb-2">Noted: Buat score untuk setiap soal.</small>
                                    <input type="text" name="score[]" class="form-control scoreSystem" readonly>
                                    <span class="text-danger error-text score.0_error" style="font-size:13px"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">* Jawaban Benar</label>
                                    <small class="d-block mb-2">Noted: Silahkan Jawaban Benar Untuk Penilaian.</small>
                                    <input type="text" name="answer[]" class="form-control" style="text-transform:uppercase">
                                    <span class="text-danger error-text answer.0_error" style="font-size:13px"></span>
                                </div>
                                
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2">
    
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-10">
            <div class="card btn-custom-green-blue" style="cursor: pointer" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <div class="p-2 d-flex justify-content-between">
                    <h5 class="mt-2">Minimize</h5>
                    <div class="mr-5 mt-2">
                        <i class="mdi mdi-minus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@if ($test->start_test == null && $test->deadline_test == null && $test->status == 0)
    <div class="row">
        <div class="col-md-10">
            <div class="card btn-custom-green-blue" style="cursor: pointer" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">
                <div class="p-2 d-flex justify-content-between">
                    <h5 class="mt-2">Setting Jadwal Test</h5>
                    <div class="mr-5 mt-2">
                        <i class="mdi mdi-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="collapse" id="collapseExample2">
        <div class="row" >
            <div class="col-md-10">
                <div class="card" >
                    <div class="card-body">
                        <h4>Jadwal Ujian Dimulai</h4>
                        <small>* Noted : Pilih jadwal ujian yg di laksanakan pada :</small>
                        <hr>
                        <div class="form-group">
                            <label for="">* Jadwal Ujian</label>
                            <input type="datetime-local" class="form-control" name="start_test">     
                            <span class="text-danger error-text start_test_error" style="font-size:13px"></span>
                                
                        </div>
                        <div class="form-group">
                            <label for="">* Batas Akhir Pengerjaan</label><br>
                            <label for=""><small class="text-gray">Note : Deadline /  batas akhir test max(24 Jam Setelah test di buat)</small></label>
                            <input type="datetime-local" class="form-control" name="deadline_test">     
                            <span class="text-danger error-text deadline_test_error" style="font-size:13px"></span>
                                
                        </div>
        
                    
                        <button id="btn-create" type="submit" class="btn-block btn btn-custom-green">CREATE</button>
                    </div>
                </div>
            </div>    
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <small>Noted : Tambah kan soal dengan klik tombol ini.</small>
                    </div>
                    <button id="btn-create" type="submit" class="btn-block btn btn-custom-green">CREATE</button>
               
                </div>
            </div>
        </div>
    </div>
@endif
</form>
<div class="float-button-question btn" onclick="addQuestion()">
    <div class="question-plus d-flex justify-content-center align-items-center">
        <i class="mdi mdi-plus"></i>
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

// function testMiddleware(){
//     let getData = localStorage.getItem('data');

//     $.get('/admin/test/{{ $classroom->slug }}/classroom/lesson/{{ $lesson->slug }}/testcode/'+getData,function(data){
//         if(data.data != true){
//             window.location.href = '{{ route("admin.test.test",$classroom) }}';
//         }
//     })  

//     if(getData == '' || getData != '{{ $lesson->code }}'){ 
//         window.location.href = '{{ route("admin.test.test",$classroom) }}';
        
//     }  
// }
// testMiddleware();

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

//---------------------------------------------------------------------------------------------

scoreSystem($('.scoreSystem').length);

function scoreSystem(val){
    if(val == 1){

        $.get('{{ route("teacher.lesson.test.question.questionCheck",[$classroom,$lesson,$test]) }}',function(data) {
            let hightValue = 100;
            
            val += data.questions.length;
            let result = 100 / val;

            if(val == 2 || val == 4 || val == 5 || val == 10 || val == 20 || val == 25 || val == 50 || val ==100){
                $('.row').find('.scoreSystem').val(result);
            }
            else{
                $('.row').find('.scoreSystem').val(result.toFixed(1));
            }
        });
    }

    else if(val > 1){
       $.get('{{ route("teacher.lesson.test.question.questionCheck",[$classroom,$lesson,$test]) }}',function(data) {
            
            val += data.questions.length;
            
            let hightValue = 100;
            let result = 100 / val;
            

            if(val == 2 || val == 4 || val == 5 || val == 10 || val == 20 || val == 25 || val == 50 || val ==100){
                $('.row').find('.scoreSystem').val(result);
            }
            else{
                $('.row').find('.scoreSystem').val(result.toFixed(1));
            }
       })

    }
}
console.log(Math.round(97.5));
//---------------------------------------------------------------------------------------------
let i = 1;
let htmlView = '';

function addQuestion(){
    i++;
    var x = i;
    viewHtml =  '<div class="row group group-remove'+i+'">' +
                    '<div class="col-md-10  ">' +
                        '<div class="card card'+i+'"> '+
                            '<div class="card-body-append card-body"> '+
                                '<div class="float-right number px-3 py-2" style="background: #8bc34a;box-shadow:0 30px 60px -12px rgb(50 50 93 / 25%), 0 18px 36px -18px rgb(0 0 0 / 30%), 0 -12px 36px -8px rgb(0 0 0 / 3%);position: relative;color: white;top: -44px; border-radius:100px;">No. '+i+'</div>'   +
                                '<div class="form-group"> ' +
                                   ' <label for="">*Pertanyaan</label> ' +
                                   ' <small class="d-block mb-2">Noted : Tulis pertanyaan.</small> ' +
                                   ' <textarea class="editor'+i+' editor-edit" name="question[]"></textarea> ' +
                                   '<span class="text-danger error-text question.'+(i -1)+'_error" style="font-size:13px"></span>' +
                                '</div> ' +
                                 '<div class="form-group">' +
                                   ' <label for="">*Option1</label>' +
                                   ' <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>' +
                                   ' <input type="text" name="option1[]" class="form-control">' +
                                   '<span class="text-danger error-text option1.'+(i -1)+'_error" style="font-size:13px"></span>' +
                                '</div>' +
                                 '<div class="form-group"> '+
                                   ' <label for="">*Option2</label> '+
                                    '<small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small> '+
                                   ' <input type="text" name="option2[]" class="form-control"> '+
                                   '<span class="text-danger error-text option2.'+(i -1)+'_error" style="font-size:13px"></span>' +
                               ' </div> '+

                                '<div class="form-group"> '+
                                   ' <label for="">*Option3</label> '+
                                    '<small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small> '+
                                   ' <input type="text" name="option3[]" class="form-control"> '+
                                   '<span class="text-danger error-text option3.'+(i -1)+'_error" style="font-size:13px"></span>' +
                                '</div>'+
                                 '<div class="form-group">'+
                                  '  <label for="">*Option4</label>'+
                                   ' <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>'+
                                   ' <input type="text" name="option4[]" class="form-control">'+
                                   '<span class="text-danger error-text option4.'+(i -1)+'_error" style="font-size:13px"></span>' +
                               ' </div>'+
                                 '<div class="form-group">'+
                                  '  <label for="">*Option5</label>'+
                                   ' <small class="d-block mb-2">Noted: Silahkan tulis jawaban.</small>'+
                                   ' <input type="text" name="option5[]" class="form-control">'+
                                   '<span class="text-danger error-text option5.'+(i -1)+'_error" style="font-size:13px"></span>' +
                               ' </div>'+
                               '<div class="form-group">' +
                                    '<label for="">*Score</label>' +
                                    '<small class="d-block mb-2">Noted: Buat score untuk setiap soal.</small>' +
                                    '<input type="text" name="score[]" class="form-control scoreSystem" value="'+i+'" readonly>' +
                                   '<span class="text-danger error-text score.'+(i -1)+'_error" style="font-size:13px"></span>' +
                               ' </div>' +
                               ' <div class="form-group">' +
                                    '<label for="">* Jawaban Benar</label>' +
                                    '<small class="d-block mb-2">Noted: Silahkan Jawaban Benar Untuk Penilaian.</small>' +
                                    '<input type="text" name="answer[]" class="form-control">' +
                                   '<span class="text-danger error-text answer.'+(i -1)+'_error" style="font-size:13px"></span>' +
                               ' </div>' +

                            '</div> '+
                        '</div> ' +
                    '</div>' + 
                    '<div class="col-md-2">' +
                        '<div class="d-flex "> ' +
                            '<button type="button" class="btn btn-info mt-2 text-white" id="btn-plus"><i class="mdi mdi-minus"></i></button>' +
                        '</div>' +
                    '</div>'+
                '</div>';
    
    // $('.card-body-append',$(this)).text(i);ClassicEditor
    
                            
    $('.append-question').append(viewHtml);
    
    scoreSystem($('.scoreSystem').length);
    

ClassicEditor
    .create( document.querySelector( '.editor'+i ) )
    .catch( error => {
        console.error( error );
    } );
}

$(document).on('click','button#btn-plus',function(e){
    e.preventDefault();
    // $('.group-remove'+value).remove();
    $(this).closest('.group').remove();
    
    scoreSystem($('.scoreSystem').length);

    i--;
    
    $('.group').each(function (c) {
        
        // console.log($('.scoreSystem').length);
        $('.number',$(this)).text('No. '+(c+2));
        
        $(this).find('.editor-edit').removeClass('editor'+(c+3)).addClass('editor'+(c+2));

       
    });

})



$('#formData').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: "{{ route('teacher.lesson.test.question.store',[$classroom,$lesson,$test]) }}",
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
                window.location.href = '{{ route("teacher.lesson.test.question.create",[$classroom,$lesson,$test]) }}';
                console.log(res);
            }
        },
        error(err){
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('[class*="'+prefix+'_error"]').text(val[0]);
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