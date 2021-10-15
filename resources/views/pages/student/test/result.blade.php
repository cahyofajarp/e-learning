@extends('app.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card-header p-4 " style="background: #3f51b5;color:white;border-radius:100px;position: relative;top:28px;z-index:2">
            <div class="ml-4">
                <h3>Hasil Test Anda</h3>
                <small>* Jika belum lulus jangan patah semangat, jangan menyerah!</small>
            </div>
        </div>
        <div class="card" style="color: black">
            <div class="card-body">
                
                <table class="table table-striped mt-4 table-borderless" style="color: black">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td><b>{{ Auth::user()->student->name }}</b></td>
                    </tr> 
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>
                            <b>{{ Auth::user()->student->classroom->levelclass->name }}</b> - 
                            <b>{{ Auth::user()->student->classroom->name }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Ujian</td>
                        <td>:</td>
                        <td>
                            <b>{{$test->name }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <h3>NILAI</h3>
                        </td>
                    </tr>
                    <tr>
                        <td>Jumlah Soal</td>
                        <td>:</td>
                        <td>{{ $result->jml_soal }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Benar</td>
                        <td>:</td>
                        <td>{{ $result->jml_benar }}</td>
                    </tr>
                    
                    <tr>
                        <td>Jumlah Salah</td>
                        <td>:</td>
                        <td>{{ $result->jml_salah }}</td>
                    </tr>
                    <tr>
                        <td>Tidak Dijawab <small>(Tidak di jawab = salah)</small></td>
                        <td>:</td>
                        <td>{{ $result->tidak_terjawab }}</td>
                    </tr>
                    <tr>
                        <td>SCORE</td>
                        <td>:</td>
                        <td>{{ $result->score }}</td>
                    </tr>
                    <tr>
                        <td>STATUS</td>
                        <td>:</td>
                        <td><div style="font-size: 30px" class="badge py-2 px-4 {{ $result->status == 'Lulus' ? 'badge-success' : 'badge-danger' }}">{{ $result->status }}</div>
                        </td>
                    </tr>
                </table>

                <a href="{{ route('student.test.startTest.preview',[$lesson,$classroom,$test,$result]) }}" class="btn alert-warning mt-5"> <i class="mdi mdi-clipboard-check"></i> Lihat Detail Test</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('add-script')
<script>
$(document).ready(function() {

function preventBack() { window.history.forward(); }  
setTimeout("preventBack()", 0);  

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
            if(response.success == false){
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