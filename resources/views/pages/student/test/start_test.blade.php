@extends('app.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <p>Noted : Klik Start Test di bawah untuk melanjutkan.</p>
            <ul>
                <li>Jangan lupa membaca doa</li>
                <li>Waktu Ujian {{ $test->time }} Menit</li>
                <li>Setiap peserta wajib melihat jadwal ujian dengan cermat untuk menghindari kesalahan.</li>
                <li>Good Luck</li>
            </ul>
        </div>
        <a href="{{ $result ? '#' : route('student.test.startTest.start',[$lesson,$classroom,$test]) }}">
            <div class="card">
                <div class="card-body alert-success" style="border:2px dotted #126943">
                    <h3> <i class="mdi mdi-play-circle"></i> START TEST </h3>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@push('add-script')
<script>

function disableBack() { window.history.forward(); }
setTimeout("disableBack()", 0);
window.onunload = function () { null };

$(document).ready(function() {


middlewareTest();

function middlewareTest(){
    $.ajax({
        url: '{{ route('student.test.startTest.middleware',[$lesson,$classroom,$test]) }}',
        type: 'POST',
        data : {
            _token: '{{ csrf_token() }}'
        },
        dataType:'json',
        success:function(response){
            console.log(response);
            if(response.success == true){
                // location.reload();
                // document.getElementById("quiz").reset(); 
                window.location.href="{{ route('student.test.index',$lesson) }}";
               
            }
        }
    });
}

})
</script>
@endpush