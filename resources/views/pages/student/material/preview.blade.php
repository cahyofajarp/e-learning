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
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="title-work">
                    <h2 style="color:#137333;font-family:'Roboto',Helvetica,Arial,sans-serif;font-weight:300">
                        {{ $work->title }} • {{ $lesson->name }}
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
                    {{-- <div class="float-right" style="font-weight:400;font-family:'Roboto',Helvetica,Arial,sans-serif;font-size: 14px;color:#5f6368;">
                            •  Due 
                            @if (\Carbon\Carbon::parse($work->due)->isoFormat('D') == \Carbon\Carbon::now()->isoFormat('D'))
                               <span class="blink_me"> Today  {{ \Carbon\Carbon::parse($work->due)->isoFormat('HH:mm') }}</span>
                            @else 
                                {{ \Carbon\Carbon::parse($work->due)->isoFormat('D MMMM') }}
                            @endif 

                           @if ($work->type == 'TUGAS')
                                @if (\Carbon\Carbon::now() > $work->due)
                                    <span class="blink_me" style="color:red">Tugas Sudah Berakhir :'(</span>
                                @endif
                           @endif
                    </div> --}}
                    <hr style="height:0.1px;background-color:#137333">
                </div>
                <div class="desciption mt-3 mb-5">
                    {!! $work->description !!}
                </div>
                <hr style="border-style: dotted">
                <div class="file-works mt-5">
                    @foreach ($work->fileworks as $filework)   
                    <div class="d-inline ml-4">
                        <embed id="" width="30%" name="plugin" src="{{ Storage::url($filework->file) }}" type="application/pdf" style="height:300px" >
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



</script>
@endpush