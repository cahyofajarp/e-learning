@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')
<style>
.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
<!-- Modal -->
<div class="modal fade" id="modalScore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
         <img src="{{ asset('images/6663-min.jpg') }}" alt="" width="100%">
        </div>
        <div class="modal-body">
          <form id="formData" action="{{ route('teacher.work.student.score.create',[$classroom,$lesson,$work,$student]) }}" method="post">
              @csrf
              <div class="form-group">
                  <label for="">Score</label>
                  <small class="d-block mb-2">Noted : Nilai ini akan masuk ke dalam nilai tugas. Wajib angka (1-100)</small>
                  <input type="number" min="0" max="100" name="score" class="form-control" id="score" value="{{ $student->materialworks->where('work_id',$work->id)->first()->answerwork ? $student->materialworks->where('work_id',$work->id)->first()->answerwork->score : ''}}">     
                  <span class="text-danger error-text score_error" style="font-size:13px"></span>
               
              </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-block btn-custom-green" id="btn-save" {{ $student->materialworks()->where('work_id',$work->id)->first()->material_file != '-' ? '' : 'disabled style=cursor:no-drop;' }}>SAVE</button>
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
                S.Work Management
            </a>
        </li> 
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                {{ $classroom->levelclass->name }} {{ $classroom->name }}
            </a>
        </li>
        <li class="breadcrumb-item" style="color:#6c757d">
            {{ $lesson->name }}
        </li>
        <li class="breadcrumb-item" style="color:#6c757d">
          <b> {{ $student->name }}</b>
        </li>
        
        <li class="breadcrumb-item" style="color:black">
            Score
        </li>
        
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Data Tugas.</h5>
                <table class="table table-striped">
                    <tr>
                        <td>Nama </td>
                        <td>:</td>
                        <td>{{ $student->name }}</td>
                    </tr> 
                    <tr>
                       <td>Kelas </td>
                       <td>:</td>
                       <td>{{ $classroom->levelclass->name }} {{ $classroom->name }}</td>
                   </tr>
                   <tr>
                      <td>Mata Pelajaran </td>
                      <td>:</td>
                      <td>{{ $lesson->name }}</td>
                  </tr>
                  <tr>
                     <td>Batas Pengerjaan (Due) </td>
                     <td>:</td>
                     <td>{{  \Carbon\Carbon::parse($work->due)->isoFormat('D MMMM Y - HH:mm') }}</td>
                 </tr>
                  <tr>
                     <td>Jam Dikirim </td>
                     <td>:</td>
                     <td>{{ \Carbon\Carbon::parse($student->materialworks()->first()->created_at)->isoFormat('D MMMM Y - HH:mm') }}</td>
                 </tr>
                 <tr>
                    <td>File </td>
                    <td>:</td>
                    <td>{{ $student->materialworks()->where('work_id',$work->id)->first()->material_file }} <br><br>
                        <i>Format : randomnumber_nama_nama_file.</i>  
                       @if ($student->materialworks()->where('work_id',$work->id)->first()->material_file != '-')

                       <a href="{{ route('teacher.work.student.file.download',[$classroom,$lesson,$work,$student]) }}"><i class="mdi mdi-download"></i> Download File</a>
                        - 
                       <a target="_blank" href="{{ asset($student->materialworks()->where('work_id',$work->id)->first()->material_file) }}"><i class="mdi mdi-clipboard-list" ></i> Preview File</a>
                       @endif
                    </td>
                </tr>

                </table>

                <button data-toggle="modal" data-target="#modalScore" class="btn btn-info px-3"><i class="mdi mdi-pencil"></i> BERI / UBAH NILAI</button>
            </div>
        </div>
    </div>
    @php
        $answerwork = $student->materialworks->where('work_id',$work->id)->first()->answerwork;
    @endphp
    @if ($student->materialworks->where('work_id',$work->id)->first()->answerwork)
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>SCORE SISWA : </h5>
                    @if ($answerwork->score == 100)
                    <h1 class="text-center mt-5" style="color:green">
                        {{ $answerwork->score }}
                    </h1>
                    <p class="text-center" style="color:green">Woow, Excellent Good Job Buddy :D</p>
                    
                    @elseif ($answerwork->score >= $work->standard)
                    <h1 class="text-center mt-5" style="color:green">
                        {{ $answerwork->score }}
                    </h1>
                    <p class="text-center" style="color: green">Yeayy, Good job.</p>
                    @elseif($student->materialworks->where('work_id',$work->id)->first()->material_file == '-')
                    <h1 class="text-center mt-5" style="color:#f44336">
                        {{ $answerwork->score }}
                    </h1>
                    <p class="text-center" style="color:#f44336">Oops, Kenapa kamu tidak mengerjakan buddy  :( </p>
                    @else 
                    <h1 class="text-center mt-5" style="color:#f44336">
                        {{ $answerwork->score }}
                    </h1>
                    <p class="text-center" style="color:#f44336">Oops, This student must learn again :( </p>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('add-script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>


<script>

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
            $('#btn-save').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-save').removeClass("disabled").html("SAVE").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
            if(res){
                Swal.fire(
                'Success!',
                'Successfuly added score!',
                'success'
                )   
                
                location.reload();
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
            })

            console.log(err);
        }

    })
});
</script>

@endpush