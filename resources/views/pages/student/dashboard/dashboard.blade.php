@extends('app.app')

@section('content')

<div class="row">
    
    @if ($countTest > 0)
    <div class="col-md-6">
        <div class="alert alert-info" style="border:2px dotted #313d79">
            <div class="card-body">
                <p>Ada <b> {{  $countTest }} 
                
                    </b> Test <span class="badge badge-danger" style="font-size: 9px;padding:2px 4px;
                    position: relative;
                    top: -12px;">New</span> , Ayo Selesaikan test mu! Sekarang! <br> <a class="mt-3" href="{{ route('student.test.lesson') }}" style="border-bottom:1px dotted #986837">Do It Now</a> </p> 
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-6">
        {{-- @foreach ($dataNewWork as $count) --}}
            @if ($work->works_count - $dataCount > 0)
            <div class="alert alert-warning" style="border:2px dotted #986837">
                <div class="card-body">
                    <p>Ada <b> {{  $work->works_count - $dataCount }} 
                    
                        </b> Tugas <span class="badge badge-danger" style="font-size: 9px;padding:2px 4px;
                        position: relative;
                        top: -12px;">New</span> , Ayo Selesaikan tugas mu! Sekarang! <br> <a class="mt-3" href="{{ route('student.work.lesson') }}" style="border-bottom:1px dotted #986837">Do It Now</a> </p> 
                </div>
            </div>
            @endif
        {{-- @endforeach --}}
    </div>
</div>
<div class="card-group">
    <div class="card border-right">
      <a href="{{ route('student.all.statistik') }}">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">  
                        <h2 class="text-dark mb-1 font-weight-medium"></h2>
                    </div>
                    <h5 class="text-muted font-weight-bold mb-0 w-100 text-truncate">Statistik Nilai</h5>
                    <small class="mt-3 text-dark" style="font-size: 70%!">All Score</small>
                        
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="activity"></i></span>
                </div>
            </div>
        </div>
      </a>
    </div>
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">  
                        <h2 class="text-dark mb-1 font-weight-medium"></h2>
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Kelas</h6>
                    <small class="mt-3" style="font-size: 60%!important;font-weight:bold">{{ $classroom->classroom->levelclass->name }} {{ $classroom->classroom->name  }}</small>
                        
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="home"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $lesson->count() }}</h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pelajaran
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="book-open"></i> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">{{  $countTest }} <small class="text-green">(active)</small></h2>
                   </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Test</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="clipboard"></i> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <h2 class="text-dark mb-1 font-weight-medium">{{  $work->works_count - $dataCount }}  <small>(active)</small></h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Tugas</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="clipboard"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('add-script')

@endpush