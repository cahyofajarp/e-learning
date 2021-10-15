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
<div class="modal fade" id="modalCheckClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content p-2" style="background-color: white !important">
        <div class="modal-header">
            {{-- <img src="{{ asset('images/6663-min.jpg') }}" alt="" style="  
            width: 70%;
            margin: 0 auto;
            margin-right: -10px;
        "> --}}
            <small>Noted : Kelas ini adalah kelas yang akan mengikuti ujian.</small>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <table class="table table-striped">
                    <thead class="btn-custom-green">
                        <tr>
                            <th>#</th>
                            <th>Nama Kelas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                      
                    </tbody>
                </table>
          </div>
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
        <li class="breadcrumb-item" style="color:black">
            {{ $lesson->name }}
        </li>
        <li class="breadcrumb-item" style="color:black">
            Preview
        </li>

    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="title-work">
                    <h2 style="color:#137333;font-family:'Roboto',Helvetica,Arial,sans-serif;font-weight:300">{{ $work->title }}</h2>
                    <p class="d-inline" style="color:#5f6368;font-size:0.875rem;font-weight:400;letter-spacing:1px">{{ $work->teacher->name }}</p>
                    • <span style="font-size: 13px">{{ \Carbon\Carbon::parse($work->created_at)->isoFormat('D MMMM Y HH:mm') }}</span>
                    <div class="float-right" style="font-weight:400;font-family:'Roboto',Helvetica,Arial,sans-serif;font-size: 14px;color:#5f6368;">
                            •  Due {{ \Carbon\Carbon::parse($work->due)->isoFormat('D MMMM') }} 
                            @if (\Carbon\Carbon::now() > $work->due)
                                <span class="blink_me" style="color:red">Tugas Sudah Berakhir :'(</span>
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
                    <div class="d-inline">
                        <embed id="" width="40%" name="plugin" src="{{ Storage::url($filework->file) }}" type="application/pdf" style="height:300px" >
                    </div>
                    @endforeach
           
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


</script>
@endpush