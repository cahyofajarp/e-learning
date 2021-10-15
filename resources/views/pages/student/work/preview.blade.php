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


<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="title-work">
                    <h2 style="color:#137333;font-family:'Roboto',Helvetica,Arial,sans-serif;font-weight:300">
                        {{ $work->title }}
                    </h2>
                    <p class="d-inline" style="color:#5f6368;font-size:0.875rem;font-weight:400;letter-spacing:1px">
                        {{ $work->teacher->name }}
                    </p>
                    <span>  
                    • 
                    {{ $work->type }}
                    </span>
                    •
                    <span style="font-size: 13px">
                            {{ \Carbon\Carbon::parse($work->created_at)->isoFormat('D MMMM Y HH:mm') }}
                    </span>
                    <div class="float-right" style="font-weight:400;font-family:'Roboto',Helvetica,Arial,sans-serif;font-size: 14px;color:#5f6368;">
                            •  Due 
                            @if (\Carbon\Carbon::parse($work->due)->isoFormat('D') == \Carbon\Carbon::now()->isoFormat('D'))
                               <span class="blink_me"> Today  {{ \Carbon\Carbon::parse($work->due)->isoFormat('HH:mm') }}</span>
                            @else 
                                {{ \Carbon\Carbon::parse($work->due)->diffForHumans() }}
                            @endif 

                           @if ($work->type == 'TUGAS')
                                @if (\Carbon\Carbon::now() > $work->due)
                                    <span class="blink_me" style="color:red">Tugas Sudah Berakhir :'(</span>
                                @endif
                           @endif
                    </div>
                    <hr style="height:0.1px;background-color:#137333">
                </div>
                <div class="desciption mt-3 mb-5">
                    {!! $work->description !!}
                </div>
                <hr style="border-style: dotted">
                <div class="file-works mt-5">
                    @foreach ($work->fileworks as $filework)   
                    <div class="d-inline ml-3">
                        <a href="{{ Storage::url($filework->file) }}" target="_blank">
                            
                            <embed id="" width="47%" name="plugin" src="{{ Storage::url($filework->file) }}" type="application/pdf" style="height:300px" >
                        </a>
                    </div>
                    @endforeach
           
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <form id="formData" action="{{ route('student.work.sendWork',[$lesson,$work]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h5>Tugas Kamu</h5>
                    <small>Wajib PDF!</small>
                    <hr>
                    @php
                        $data =$student->materialworks()->where('material_file','<>','-')->where('work_id',$work->id)->get()->count();
                    @endphp 
                    

                    <input type="file" class="form-control" name="file_work" {{ $data > 0 || \Carbon\Carbon::now() > $work->due ? 'disabled' : '' }}>
                    @if ($data > 0)
                        <div class="mt-3"><small>{{ $student->materialworks()->where('work_id',$work->id)->first()->material_file }}</small></div>
                        <div class="badge badge-success"><i class="mdi mdi-check"></i> Done</div>
                    @elseif(\Carbon\Carbon::now() > $work->due)
                        <span class="badge badge-warning mt-2">Whoops, anda belum mengerjakan! :(</span>
                        <div class="badge badge-danger mt-3"><i class="mdi mdi-close"></i> Tugas sudah berakhir :'(</div>
                    @endif
                    <span class="text-danger error-text file_work_error" style="font-size:13px"></span>
                                
                    <hr>
                    <button id="btn-send" type="submit" class="btn btn-success btn-block" {{ $data > 0 || \Carbon\Carbon::now() > $work->due ? 'disabled style=cursor:no-drop;' : '' }}><i class="mdi mdi-send"></i> SEND WORK</button>
                </div>
            </div>
        </form>
    </div>
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
        let url = $(this).attr('action');
        let formData = new FormData(this);    
        
        $.ajax({
            type: 'POST',
            data : formData,
            contentType: false,
            processData: false,
            url: url,
            beforeSend:function(){
                $('#btn-send').addClass("disabled").html("<i class='mdi mdi-spin mdi-loading'></i>  Processing...").attr('disabled',true);
                $(document).find('span.error-text').text('');
            },    
            complete: function(){
                $('#btn-send').removeClass("disabled").html("SEND WORK").attr('disabled',false);
                
            },
            success: function(res){
                // $("#count").text(res.count);
                console.log(res);
                if(res.success == true){
                    Swal.fire(
                    'Success!',
                    'Ujian berhasil dikirim',
                    'success'
                    )   

                    setTimeout(function() {
                        location.reload();
                    },1000)
                }
                else{
                    Swal.fire(
                    'Error!',
                    'Batas Waktu Ujian Sudah Habis!',
                    'error'
                    );
                    setTimeout(function() {
                        location.reload();
                    },600)
                }
                
            },
            error(err){
                $.each(err.responseJSON.errors,function(prefix,val) {
                    $('.'+prefix+'_error').text(val[0]);
                })
            }

        })
})

</script>
@endpush