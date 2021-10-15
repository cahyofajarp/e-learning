@extends('app.app')
@push('add-style')
    
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

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
        <form method="post" action="{{ route('admin.test.test.importExcelQuestion',[$classroom,$lesson,$test]) }}" enctype="multipart/form-data" id="formDataImport">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="" class="text-bold"><small class="text-gray">* Contoh Format Excel</small> </label>
                    <div class="table-responsive p-3">
                        <img src="{{ asset('images/question-template2.png') }}" alt="">
                    </div>
                </div>
                <div class="download">
                    <span class="text-gray" style="font-size: 13px">Or Dowload Tempelate</span>
                    <a href="" class="btn btn-sm btn-custom-blue" style="border-radius: 100px"><i class="mdi mdi-download"></i> Download Template</a>
                </div>
                <hr>
                <div class="form-group">
                    <label for="">Import File Excel</label>
                    <span class="d-block mb-1"><small>Note : Check kembali pertanyaan , ikuti template yang ada !</small></span>
                    <input type="file" name="file" class="form-control mb-1">
                    <span class="text-danger error-text file_error" style="font-size:13px"></span>
                </div>
            @if ($test->status == 0 && $test->start_test == null && $test->deadline_test == null)
       
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

            @endif
            
            <div id="success" style="display: none">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
            </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="btn-import" style="letter-spacing: 2"><i class="mdi mdi-file-import"></i> IMPORT</button>
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

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="btn-group mb-4">
                    <a href="{{ route('admin.test.test.testCreate.Questioncreate',[$classroom,$lesson,$test]) }}" class="btn btn-custom-green"> <i class="mdi mdi-plus"></i> Tambah Pertanyaan</a>
                    {{-- <a href="" class="btn btn-custom-blue"> Custom Score(Nilai)</a> --}}
                    <button href="" class="btn btn-warning ml-3" data-toggle="modal" data-target="#importExcel"><i class="mdi mdi-import"></i> Import Excel </button>
                   
                </div>

                <form action="{{ route('admin.test.test.testCreate.destroySelect',[$classroom,$lesson,$test]) }}" method="post" id="selectDel">
                @csrf
                <div class="table-responsive">
                    <table class="table table-striped" id="myTable" style="width:200%">
                        <thead class="btn-custom-green-blue">
                            <tr>
                                <th>#</th>
                                <th style="width: 20%">Pertanyaan</th>
                                <th >Option 1</th>
                                <th >Option 2</th>
                                <th >Option 3</th>
                                <th>Option 4</th>
                                <th >Option 5</th>
                                <th>Jawaban Benar (Huruf)</th>
                                <th>Score</th>
                                <th>Action | <button type="button" id="btn-select-del" class="btn btn-sm btn-danger" style="border-radius: 100px"> <i class="mdi mdi-delete"></i> Select</button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no =1; ?>
                            @foreach ($questions as $question)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{!! $question->ask  !!}</td>
                                    <td>{{ $question->option1 }}</td>
                                    <td>{{ $question->option2 }}</td>
                                    <td>{{ $question->option3 }}</td>
                                    <td>{{ $question->option4 }}</td>
                                    <td>{{ $question->option5 }}</td>
                                    <td><div class="btn btn-success">{{ $question->answer }}</div></td>
                                    <td><div class="btn btn-info">{{ $question->score }}</div></td>
                                    <td>
                                        <a href="" class="btn btn-custom-yellow btn-sm "><i class="mdi mdi-pencil"></i> Edit</a>
                                        <button class="btn btn-custom-red btn-sm" id="btn-delete" data-delete="{{ route('admin.test.question.destroy',[$classroom,$lesson,$test,$question]) }}">
                                            <i class="mdi mdi-delete"></i> Delete
                                        </button>
                                        <span class="d-none in"><input type="checkbox" id="del" name="del[]" value="{{ $question->id }}"> <small>Del</small></span>
                                    </td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>     
                <button type="submit" id="btn-selectdel" class="btn btn-danger float-right mt-2 del d-none in"> <i class="mdi mdi-delete"></i> Delete</button>
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
<script>

function testMiddleware(){
    let getData = localStorage.getItem('data');

    $.get('/admin/test/{{ $classroom->slug }}/classroom/lesson/{{ $lesson->slug }}/testcode/'+getData,function(data){
        if(data.data != true){
            window.location.href = '{{ route("admin.test.test",$classroom) }}';
        }
    })  

    if(getData == '' || getData != '{{ $lesson->code }}'){ 
        window.location.href = '{{ route("admin.test.test",$classroom) }}';
        
    }  
}
testMiddleware();

$(document).ready(function() {
    var t = $('#myTable').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": [0,9]
        } ],
        "order": []
    } );

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
});

$('button#btn-select-del').click(function(e){
    if($('.in').length - 1 > 0){
        $('.in').toggleClass('d-none');
    }
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
                    if(response.message == true){
                        Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                        location.reload();
                    }else{
                        Swal.fire(
                        'Error!',
                        response.message,
                        'error'
                        )   
                    }
                // console.log(response);
                    
                },
                error:function(err){
                    console.log(err);
                }
            });
        }
    })
});


$('#formDataImport').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: $(this).attr('action'),
        xhr : function() {
            
            var xhr = new window.XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e){
                if(e.lengthComputable){

                    var percent = Math.round((e.loaded / e.total) * 100);
                    
                    $('.progress-bar').css('width', percent + '%').text(percent + '%');
                }
            });

            return xhr;
        },
      
        beforeSend:function(){
            $('#btn-import').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
            $('#success').show();
        },    
        complete: function(){
            $('#btn-import').removeClass("disabled").html("<i class='mdi mdi-import'></i> IMPORT").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
            if(res){
                
                $('.progress-bar').css('width','100%').text('Uploaded');
                Swal.fire(
                'Success!',
                'You data is successfuly created!',
                'success'
                )   
                location.reload();
                // window.location.href = '{{ route("admin.test.test.testCreate.question",[$classroom,$lesson,$test]) }}';
                console.log(res);
            }
        },
        error(err){
          
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error').text(val[0]);
            })

            console.log(err);

            if(err){
                
                $('.progress-bar').css('width','0%').text('');

                Swal.fire(
                'Error!',
                'Check kembali ada data yg kosong / tidak sesuai!',
                'error'
                )   
            }
        }

    })
});

$('#selectDel').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({ 
       
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: $(this).attr('action'),

        beforeSend:function(){
            $('#btn-selectdel').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-selectdel').removeClass("disabled").html("<i class='mdi mdi-delete'></i> Delete").attr('disabled',false);
            
        },
        success: function(res){

            if(res.message == true){
                Swal.fire(
                'Success!',
                'You data is successfuly created!',
                'success'
                )   
                location.reload();
                // console.log(res);
            }else{
                Swal.fire(
                'Error!',
                res.message,
                'error'
                )   
            }
        },
        error(err){
          
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error').text(val[0]);
            })

            console.log(err);

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