@extends('app.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                Data Mata Pelajaran
            </div>
        </div>
    </div>
</div>
<div class="row">
    @foreach ($lessons as $lesson)
    <div class="col-md-4">
        <div class="card border-right">
            <a href="{{ route('student.lesson.statistik',$lesson->lesson) }}">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">  
                                <h2 class="text-dark mb-1 font-weight-medium">Statistik</h2>
                            </div>
                            <h5 class="text-muted font-weight-bold mb-0 w-100 text-truncate">
                                {{ $lesson->lesson->name}}</h5>
                                <hr>
                            <div class=""><small class="text-dark" style="font-size: 70%!">Pengajar : {{ $lesson->teacher->name }}</small></div>
                                
                        </div>
                        <div class="ml-auto mt-md-5 mt-lg-0">
                            <span class="opacity-7 text-muted" style="font-si"><i data-feather="activity"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endforeach


@endsection

@push('add-script')

@endpush