@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')

<div class="row mt-4">
    <div class="col-md-12">
       <div class="card">
           <div class="card-header p-4" style="    
           background: #3f51b5;
           position: relative;
           color: white;
           top: -27px;
           border-radius: 100px;">Data Nilai Mapel {{ $lesson->name }}</div>
          
           <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-light table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th>Jumlah Tugas</th>
                                    <th>:</th>
                                    <td>{{ $works->count() }} Tugas</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Test</th>
                                    <th>:</th>
                                    <td>{{ $tests->count() }} Test</td>
                                </tr>
                                <tr>
                                    <th colspan="2">TOTAL</th>
                                    <td>{{ $works->count() + $tests->count()  }}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>

                    <div class="col-md-6">
                        <h5>Noted : Cara Menilai </h5>
                        <hr>
                        <p>Nilai rata-rata = Jumlah nilai/banyaknya data</p>
                    </div>
                </div>
           </div>
       </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mt-5">
        <div class="card">  
            <div class="card-header alert-info p-4" style="position: relative;
            /* color: white; */
            top: -27px;
            border-radius: 100px;">
                Data Tugas
            </div>
            <div class="card-body">
               <div class="table-responsive">
                <table class="table table-striped d-table myTable" style="width: 150%">
                    <thead class="alert-success">
                        <tr>
                            <th>#</th>
                            <th>Nama Tugas</th>
                            <th>Tanggal Dibuat</th>
                            <th>Score</th>
                        </tr>
                        </thead>
                        <tbody>
                            {{-- <?php $no = 1;  ?>
                            @foreach ($works as $work)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $work->title }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($work->created_at)->isoFormat('D MMMM Y - HH:mm') }} </td>
                                <td>
                                    @if ($student->materialworks->where('work_id',$work->id)->first())
                                        @php
                                        $color = 'red';

                                        if($student->materialworks->where('work_id',$work->id)->first()->answerwork->score  >= $work->standard){
                                            $color = 'green';
                                        }
                                        @endphp
                                        <h4 class="text-center" style="border-bottom: 1px dotted {{ $color }} ;color:{{ $color }};font-weight:bold"> {{ $student->materialworks->where('work_id',$work->id)->first()->answerwork->score }}</h4>
                                    @else 
                                        <span class="badge badge-warning">Belum Dinilai</span>
                                    @endif
                                </td>
                            </tr>
                            <?php $no++;  ?>
                            @endforeach --}}

                            @foreach ($student->materialworks->sortByDesc('created_at') as $materi)
                                @if ($materi->work)
                                    <tr>
                                        <td></td>
                                        <td>{{ $materi->work->title }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($materi->work->created_at)->isoFormat('D MMMM Y - HH:mm') }} </td>
                                        <td>

                                            @if ($materi->answerwork)
                                                @php
                                                $color = 'red';

                                                if($materi->answerwork->score  >= $materi->work->standard){
                                                    $color = 'green';
                                                }
                                                @endphp
                                                <h4 class="text-center" style="border-bottom: 1px dotted {{ $color }} ;color:{{ $color }};font-weight:bold"> {{ $materi->answerwork->score }}</h4>
                                            @else 
                                                <span class="badge badge-warning">Belum Dinilai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                        @endforeach
                        </tbody>
                </table>
               </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-5">
        <div class="card">
            <div class="card-header alert-warning p-4" style="position: relative;
            /* color: white; */
            top: -27px;
            border-radius: 100px;">
                Data Test
            </div>
            <div class="card-body">
               <div class="table-responsive">
                <table class="table table-striped d-table table-striped myTable" style="width: 150%">
                    <thead class="alert-success">
                        <tr>
                            <th>#</th>
                            <th>Nama Test</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                            <th>Score</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;  ?>
                            @foreach ($tests->sortByDesc('created_at') as $test)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $test->name }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($test->created_at)->isoFormat('D MMMM Y - HH:mm') }} </td>
                                
                                <td> 
                                    @if ($test->results->first())
                                        @php
                                            $color = 'red';

                                            if( $test->results->first()->score >= $test->standard){
                                                $color = 'green';
                                            }
                                        @endphp
                                       
                                        <h4 style="color:{{ $color }};font-weight:bold"> {{ $test->results->first()->status }}</h4>
                                       
                                    @else 
                                        
                                    @endif
                                </td>
                                <td>
                                    @if ($test->results->first())
                                        @php
                                            $color = 'red';

                                            if( $test->results->first()->score >= $test->standard){
                                                $color = 'green';
                                            }
                                        @endphp
                                        <h4 class="text-center" style="border-bottom: 1px dotted {{ $color }} ;color:{{ $color }};font-weight:bold">{{ $test->results->first()->score }}</h4>
                                        
                                    @else 
                                        -
                                    @endif
                                </td>
                            </tr>
                            <?php $no++;  ?>
                            @endforeach
                        </tbody>
                </table>
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
    $(document).ready(function() {
        var t = $('.myTable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": []
        } );
    
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });

    

</script>
@endpush