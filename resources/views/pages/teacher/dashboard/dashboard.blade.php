@extends('app.app')

@section('content')

{{-- Data Rata Rata Semua Nilai Siswa Disini--}}

<div class="card-group">
    <div class="card border-right">
        <a href="{{ route('teacher.classroom') }}">
         <div class="card-body">
             <div class="d-flex d-lg-flex d-md-block align-items-center">
                 <div>
                     <div class="d-inline-flex align-items-center">
                         <h5 class="text-dark mb-1 font-weight-medium">Statistik</h5>
                     </div>
                     <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Work Siswa</h6>
                 </div>
                 <div class="ml-auto mt-md-3 mt-lg-0">
                     <span class="opacity-7 text-muted"><i data-feather="activity"></i></span>
                 </div>
             </div>
         </div>
        </a>
     </div>    
     
     <div class="card border-right">
        <a href="{{ route('teacher.test.statistik.classroom') }}">
         <div class="card-body">
             <div class="d-flex d-lg-flex d-md-block align-items-center">
                 <div>
                     <div class="d-inline-flex align-items-center">
                         <h5 class="text-dark mb-1 font-weight-medium">Statistik</h5>
                     </div>
                     <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Test Siswa</h6>
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
                        <h2 class="text-dark mb-1 font-weight-medium">{{ $classroom }}</h2>
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Kelas</h6>
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
                    <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $test }}</h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Test
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="clipboard"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">{{ $lesson }}</h2>
                   </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pelajaran</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="book-open"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <h2 class="text-dark mb-1 font-weight-medium">{{ $student }}</h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Student</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection