@extends('app.app')
@push('add-style')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">


@endpush
@section('content')
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
table {
    font-size: 15px;
    font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif
}
</style>
<div class="alert-info mb-5" style="border:2px dotted #313d79">
    <div class="card-body">
        <p>Data Statistik Nilai Siswa Kelas <b> {{ $classroom->levelclass->name }} {{ $classroom->name }}</b> Mata Pelajaran <b>{{ $lesson->name }}</b></p>
        <h5>Nama Siswa : <b>{{ $student->name }}</b></h5>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card alert-success" style="border:1px dotted #126948">
            <div class="card-body">
                <h4>NOTE</h4>
                <hr>
                <h5 style="font-weight: 400">Rumus : Jumlah nilai tugas / banyak tugas.</h5>
                <p>Jumlah Nilai Per Bulan = 900 </p>
                <p>Jumlah Banyak Tugas = 10</p>
                <p>900 / 10 = <b>90</b> </p>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4>Export</h4>
                <p>Note : Export ke excel data rata rata siswa selama setahun.</p>
                <a href="{{ route('teacher.statistik.student.exportWorkStudent',[$classroom,$lesson,$student]) }}" class="btn btn-success mt-1"><i class="mdi mdi-export"></i> Export to Excel</a>
                <a href="" class="btn btn-danger mt-1"><i class="mdi mdi-file-pdf"></i> Export to PDF</a>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header alert-warning" style="border-radius: 5px"">
                <h3 style="font-weight: 100">Statistik Rata Rata Nilai Tugas Siswa</h3>
            </div>
            <div class="card-body">
                <p class="py-4" style="border-bottom: 1px dotted #126948">Current Year : <b>{{ \Carbon\Carbon::now()->isoFormat('Y') }}</b></p>
                <h5 class="mb-3">Cari Tahun :  </h5>
                <div class="row mb-5">
                    <div class="col-md-10">
                        <form action="{{ route('teacher.statistik.student.statistik',[$classroom,$lesson,$student]) }}" class="form-inline" method="GET" id="searchYearAjaxForm">
                            
                            <select name="searchByYear" id="ss" class="form-control select2" style="width: 300px">
                                @for ($i = 2015; $i <= 2050; $i++)
                                    <option value="{{ $i }}"
                                    @if (request()->searchByYear)
                                        @if ($i == request()->searchByYear)
                                            selected
                                        @endif
                                    @else
                                        @if ($i == date('Y'))
                                            selected
                                        @endif
                                    @endif
                                >{{ $i }}</option>
                                @endfor
                            </select>
                            @if (request()->from_date && request()->to_date)
                                
                            <input type="hidden" name="from_date" id="" class="form-control" value="{{ request()->from_date}}">
                                
                            <input type="hidden" name="to_date" id="" class="form-control" value="{{ request()->to_date}}">
                           
                            @endif
                            <button class="btn btn-success px-2 ml-2" type="submit" id="btn-search-year"><i class="mdi mdi-book"></i> Cari</button>
                        </form>
                    </div>
                </div>
                    <div id="chartdiv"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header btn-custom-green" style="border-radius: 5px">
               <h2 class="text-left" style="font-weight: 100"> Data Semua Tugas!</h2>
                <hr>
                <h3 class=""  style="font-weight: 100">Total Tugas : <b>{{ $lesson->works->where('type','TUGAS')->count() }}</b></h3>
            </div>
            <div class="card-body">
                <h5 class="mb-3">Cari Tanggal : </h5>
                <div class="row mb-5">
                    <div class="col-md-10">
                        <form action="{{ route('teacher.statistik.student.statistik',[$classroom,$lesson,$student]) }}" class="form-inline" method="GET"  id="searchYearAjaxForm">
                           @if (request()->searchByYear)
                           <input type="hidden" name="searchByYear" value="{{ request()->searchByYear }}">
                            
                           @endif
                            <div class="form-group">
                                <input type="date" name="from_date" id="" class="form-control" value="{{ request()->from_date}}">
                            </div>
                            <span class="px-3">-</span>
                            <div class="form-group">
                                <input type="date" name="to_date" id="" class="form-control" value="{{ request()->to_date}}">
                            </div>

                            <button class="btn btn-success px-2 ml-2" type="submit" ><i class="mdi mdi-book"></i> Cari</button>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-responsive" id="myTable" style="width: 150%">
                        <thead class="btn-warning">
                            <tr>
                                <th  style="width: 3%">#</th>
                                <th>Title Tugas</th>
                                <th>Tanggal Tugas Dibuat</th>
                                <th><b>Tanggal Tugas Dikirim</b></th>
                                <th>Tanggal Batas Tugas</th>
                                <th>Status Pengerjaan</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                        </thead> 
                        <tbody>
                                @foreach ($materialwork->sortByDesc('created_at') as $material)
                                <tr>
                                    <td></td>
                                    <td>{{ $material->work->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($material->work->created_at)->isoFormat('D MMMM Y - HH:mm') }}</td>
                                    <td>
                                        @php
                                            $send = '-';

                                            if($material->material_file != '-'){
                                                $send =  \Carbon\Carbon::parse($material->created_at)->isoFormat('D MMMM Y - HH:mm'); 
                                            }
                                        @endphp
                                        {{ $send }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($material->due)->isoFormat('D MMMM Y - HH:mm') }}
                                    </td>
                                    <td>
                                        @if ($material->material_file == '-')
                                            <span class="badge badge-danger badge-lg"><i class="mdi mdi-close"></i> Not Finished</span>
                                        @else 
                                            <span class="badge badge-success"><i class="mdi mdi-check"></i> Done</span>
                                        @endif
                                    </td>
                                    <td>    
                                        @php
                                        $score = '';
                                        $color = '';
                                        if($material->answerwork){

                                            $color = 'red';

                                            if( $material->answerwork->score >= $material->work->standard){
                                                $color = 'green';
                                                $score = $material->answerwork->score;
                                            }
                                        }
                                        else{
                                            $score = 'Belum Dinilai';
                                            $color = '#fdc16a';
                                        }
                                        @endphp
                                        <h4 class="text-center" style="border-bottom: 1px dotted {{ $color }} ;color:{{ $color }};font-weight:bold">{{ $score }}</h4>
                                    </td>
                                    <td>
                                    
                                        <a href="{{ route('teacher.statistik.student.work.preview',[$classroom,$lesson,$material->work]) }}" class="btn btn-info btn-sm px-4 py-2" style="border-radius: 100px"><i class="mdi mdi-eye"></i> Lihat Tugas</a>
                                    </td>
                                </tr>
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

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script>

$(document).ready(function() {
    $('.select2').select2({
        theme: "bootstrap"
    });
});
</script>
<script>

</script>
<script>


    $(document).ready(function() {
        var t = $('#myTable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": [0,6,7]
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

<script>

loadDiagram(searchByYear= '{{ date("Y") }}');

function loadDiagram(searchByYear = null){

    $.ajax({
        type: 'GET',
        data : {
            searchByYear:searchByYear,
        },
        url: '{{ route('teacher.statistik.student.statistik',[$classroom,$lesson,$student]) }}',
        beforeSend:function(){
            $('#chartdiv').html('<h4 class="text-center">Loading ...</h4>');
        },
        success: function(res){
            console.table(res);
            // console.log(res);
             am4core.ready(function() {
                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end
                
                var chart = am4core.create("chartdiv", am4charts.XYChart);
                chart.hiddenState.properties.opacity = 0; 
                
                chart.data = res.data;
                
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataFields.category = "month";
                categoryAxis.renderer.minGridDistance = 40;
                categoryAxis.fontSize = 11;
                
                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.min = 0;
                valueAxis.max = 150;
                valueAxis.strictMinMax = true;
                valueAxis.renderer.minGridDistance = 30;
                
                
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.dataFields.categoryX = "month";
                series.dataFields.valueY = ['avg'];
                series.columns.template.tooltipText = "{valueY.value}";
                series.columns.template.tooltipY = 0;
                series.columns.template.strokeOpacity = 0;
                
                // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
                series.columns.template.adapter.add("fill", function(fill, target) {
                    return chart.colors.getIndex(target.dataItem.index);
                });
    
            }); // end am4core.ready()
        },
        error(err){
            console.log(err);
        }

    })
}



$('#searchYearAjaxForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        type: 'GET',
        data : formData,
        url: $(this).attr('action'),
        beforeSend:function(){
            $('#btn-search-year').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-search-year').removeClass("disabled").html('<i class="mdi mdi-book"></i> Cari').attr('disabled',false);
            
        },
        success: function(res){
            am4core.ready(function() {
                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end
                
                var chart = am4core.create("chartdiv", am4charts.XYChart);
                chart.hiddenState.properties.opacity = 0; 
                
                chart.data = res.data;
                
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataFields.category = "month";
                categoryAxis.renderer.minGridDistance = 40;
                categoryAxis.fontSize = 11;
                
                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.min = 0;
                valueAxis.max = 150;
                valueAxis.strictMinMax = true;
                valueAxis.renderer.minGridDistance = 30;
                
                
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.dataFields.categoryX = "month";
                series.dataFields.valueY = ['avg'];
                series.columns.template.tooltipText = "{valueY.value}";
                series.columns.template.tooltipY = 0;
                series.columns.template.strokeOpacity = 0;
                
                // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
                series.columns.template.adapter.add("fill", function(fill, target) {
                    return chart.colors.getIndex(target.dataItem.index);
                });
    
            }); // end am4core.ready()
        },
        error(err){
            console.log(err);
        }

    })
});
</script>
@endpush