@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


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
                {{ auth()->user()->student->classroom->levelclass->name }} {{ auth()->user()->student->classroom->name }}
            </a>
        </li>
        <li class="breadcrumb-item" style="color:#6c757d">
            {{ $lesson->name }}
        </li>

    </ol>
</nav>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                
                @if ($work->due < \Carbon\Carbon::now())
                    <table class="table table-striped">
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ auth()->user()->student->name }}</td>
                        </tr>      
                        <tr>
                            <td>Kelas</td>
                            <td>:</td>
                            <td> {{ auth()->user()->student->classroom->levelclass->name }} {{ auth()->user()->student->classroom->name }}</td>
                        </tr>
                        <tr>
                            <td>Mata Pelajaran</td>
                            <td>:</td>
                            <td>{{ $lesson->name }}</td>
                        </tr>
                        <tr>
                            <td>Title Tugas</td>
                            <td>:</td>
                            <td>{{ $work->title }}</td>
                        </tr>
                        <tr>
                            <td>Berakhir Tugas (due)</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($work->due)->isoFormat('D MMMM Y - HH:mm') }}</td>
                        </tr>
                        <tr>
                            <td>Jam Dikirim</td>
                            <td>:</td>
                            <td>
                                @if ($student->materialworks()->where('work_id',$work->id)->first())
                                {{  \Carbon\Carbon::parse($student->materialworks()->where('work_id',$work->id)->first()->created_at)->isoFormat('D MMMM Y - HH:mm')  }}
                                @else 
                                -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>
                                @if ($student->materialworks()->where('work_id',$work->id)->where('material_file','<>','-')->first())
                                    <span class="badge badge-success">Done <i class="mdi mdi-check"></i></span>
                                @else 
                                    <span class="badge badge-danger">Belum Dikerjakan <i class="mdi mdi-close"></i></span>
                                @endif
                            </td>
                        </tr>
                    </table>
                        
                    <div class="score">
                        <p>Score Anda : </p>
                        @if ($student->materialworks()->where('work_id',$work->id)->first())
                            @php
                                $score = auth()->user()->student->materialworks->where('work_id',$work->id)->first()->answerwork->score;
                            @endphp
                            @if ($student->materialworks()->where('work_id',$work->id)->first()->answerwork)
                                
                                @if ( $score == 100)
                                    
                                    <h1 class="ml-5 mt-5" style="color:green;font-size:70px">
                                        {{  $score }}
                                    </h1>

                                    <p class="" style="color:green">Woow, Excellent Good Job Buddy :D</p>
                                    
                                @elseif ($score >= $work->standard)
                                    
                                    <h1 class="ml-5 mt-5" style="color:green;font-size:70px">
                                        {{ $score }}
                                    </h1>
                                    
                                    <p class="" style="color: green">Yeayy, Good job.</p>
                                
                                @elseif($score == 0) 
                                    
                                    <h1 class="ml-5 mt-5" style="color:#f44336;font-size:70px">
                                        0
                                    </h1>

                                    <p class="" style="color:#f44336"><b>Oops, Kenapa anda tidak megerjakan tugas (</b> </p>
                                @else 

                                    <h1 class="ml-5 mt-5" style="color:#f44336;font-size:70px">
                                        {{  $score }}
                                    </h1>

                                    <p class="" style="color:#f44336">Oops, This student must learn again :( </p>
                                @endif
                            @else
                            <h1 class="ml-5 mt-5" style="color:#ff9800;font-size:70px">
                                -
                            </h1>
                            
                            <p class="" style="color:#ff9800">Whoops, Sepertinya tugas anda belum dinilai :( </p>
                            <small>Silahlan hubungi guru anda untuk penjelasan lebih lanjut, Thanks.</small>
                            @endif
                        
                        @endif

                    </div>

                @elseif(auth()->user()->student->materialworks->where('work_id',$work->id)->count() == 0)

                    <h3 class="text-center">Oops, Kerjakan dulu tugas mu ya student.</h3>
                    <p class="text-center">Jangan sampai telat, Due Tugas sampai <b>{{ \Carbon\Carbon::parse($work->due)->isoFormat('D MMMM Y - HH:mm') }}</b> </p>
                    <img style="margin: 0 auto" class="d-flex justify-content-center" src="{{ asset('images/5836-min.jpg') }}" alt="" width="40%">
               
                @else

                    <h3 class="text-center d-flex justify-content-center" style="margin:0 auto; width:64%">Tugas masih di proses sampai waktu pengerjaan tugas berakhir.</h3>
                    <p class="mt-5">Due Tugas : <b>{{ \Carbon\Carbon::parse($work->due)->isoFormat('D MMMM Y - HH:mm') }}</b> </p>
               
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('add-script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>


@endpush