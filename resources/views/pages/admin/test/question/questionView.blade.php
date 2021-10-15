@extends('app.app')

@section('content')
<style>
p{
    display: inline;
}
input[type=radio]{
    margin-top: 10px;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="header-text">
                    <h4>Question View</h4>
                    <small>Noted : Ketika test sudah di mulai tambah kan soal akan berubah menjadi lihat soal.</small>
                </div>
                <hr>
                <div class="ask-content">
                    <?php $no =1; ?>
                    @foreach ($questions as $no => $question)
                        <div class="question mb-3">
                          <div class="question-ask mb-2"><span>{{ ($no) }}.</span> {!! $question->ask !!}</div>
                          <div class="option">
                            <input type="radio" name="option[{{ $key }}]" {{ $question->answer == 'A' ? 'checked' : ''}} disabled> <span class="badge {{ $question->answer == 'A' ? 'badge-success' : ''}}" style="font-size: 14px"> {{ $question->option1 }}</span> <br>
                            <input type="radio" name="option[{{ $key }}]" {{ $question->answer == 'B' ? 'checked' : ''}} disabled> <span class="badge {{ $question->answer == 'B' ? 'badge-success' : ''}}" style="font-size: 14px"> {{ $question->option2 }} </span><br>
                            <input type="radio" name="option[{{ $key }}]" {{ $question->answer == 'C' ? 'checked' : ''}} disabled> <span class="badge {{ $question->answer == 'C' ? 'badge-success' : ''}}" style="font-size: 14px"> {{ $question->option3 }}</span> <br>
                            <input type="radio" name="option[{{ $key }}]" {{ $question->answer == 'D' ? 'checked' : ''}} disabled> <span class="badge {{ $question->answer == 'D' ? 'badge-success' : ''}}" style="font-size: 14px"> {{ $question->option4 }}</span> <br>
                            <input type="radio" name="option[{{ $key }}]" {{ $question->answer == 'E' ? 'checked' : ''}} disabled> <span class="badge {{ $question->answer == 'E' ? 'badge-success' : ''}}" style="font-size: 14px"> {{ $question->option5 }}</span> <br>
                          </div>
                        </div>
                    <?php $no++ ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('add-script')
<script>
function testMiddleware(){
    let getData = localStorage.getItem('data');

    $.get('/admin/test/{{ $classroom->slug }}/classroom/lesson/{{ $lesson->slug }}/testcode/'+getData,function(data){
        if(data.data != true){
            window.location.href = '{{ route("admin.test.test",$classroom) }}';
        }
    })  

    if(getData == '' || getData != '{{ $lesson->code }}'){ 
        window.location.href = '{{ route("admin.test.test",$classroom) }}';
        
    }  
}
testMiddleware();

</script>
@endpush