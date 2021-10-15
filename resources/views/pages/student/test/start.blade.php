@extends('app.app')

@section('content')
<style>

#timer {
    font-size: 20px;
    color: #db4844;
}
.start-test{
    float: right;
    position: fixed;
    background: whitesmoke;
    padding: 10px;
    border-radius: 7px;
    right: 0;
    top: 340px;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            
            <div class="card-body">
                <table class="table table-borderless table-striped">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td> {{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td> {{ $classroom->levelclass->name }} {{ $classroom->name }}</td>
                    </tr>
                    <tr>
                        <td>Nama Ujian</td>
                        <td>:</td>
                        <td> {{ $test->name }}</td>
                    </tr>
                </table>
            </div>
        </div>
        {{-- {{  ? 'sudah test' : 'belum selesai' }} --}}
        <div class="card">
            <div class="card-body">
                <div class="start-test">
                    
                <div id="timer"></div>
                </div>
                <form action="{{ route('student.test.startTest.store',[$lesson,$classroom,$test,$result]) }}" method="post" id="quiz" name="quiz">
                    
                    @csrf
                    @foreach ($questions as $no => $question)
                   
                    <div class="d-block mt-3">
                        <div class="question-ask mb-2 d-flex"><span>{{ $no+1 }}.</span> <span>{!! $question->ask !!}</span></div>
                        <div class="option mt-1">
                            <div class="form-group" id="dataID" data-id="">
                                <input type="hidden" value="{{ $question->id }}" name="question_id[{{ $no }}]">   
                                <input type="hidden" value="NULL" name="correct[{{ $no }}]" id="checkifnull">                         
                                <input required  id="check"  type="radio" value="A" class="" name="correct[{{$no}}]"> A.{{ $question->option1 }} <br>
                                <input required  id="check" type="radio" value="B" class="" name="correct[{{$no}}]"> B.{{ $question->option2 }} <br>
                                <input required  id="check" type="radio" value="C" class="" name="correct[{{$no}}]"> C.{{ $question->option3 }} <br>
                                <input required  id="check" type="radio" value="D" class="" name="correct[{{$no}}]"> D.{{ $question->option4 }} <br>
                                <input required  id="check" type="radio" value="E" class="" name="correct[{{$no}}]" > E.{{ $question->option5 }} <br>
                                <input type="hidden" name="answer[{{ $no}}]" value="{{ $question->answer}}">
                                <input type="hidden" name="score[{{ $loop->index}}]" value="{{ $question->score}}">
        
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <button id="btn-finish" class=" btn btn-success mt-5" type="submit">FINISH</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('add-script')
<script>
// Set the date we're counting down to


function disableBack() { window.history.forward(); }
setTimeout("disableBack()", 0);
window.onunload = function () { null };

$(document).ready(function() {


// window.onbeforeunload = function(){
//   return 'Are you sure you want to leave?';
// };


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
                $('#btn-finish').attr('disabled'); 
                $('#quiz').css({'display':'none'});
                window.location.href="{{ route('student.test.index',$lesson) }}";
               
            }
            if(response.success == false){
                var countDownDate = new Date(response.start_time).getTime();

                // Update the count down every 1 second
                var x = setInterval(function() {

                // Get today's date and time
                var now = new Date().getTime();
                    
                // Find the distance between now and the count down date
                var distance = countDownDate - now;
                    
                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                // Output the result in an element with id="demo"
                document.getElementById("timer").innerHTML ="Time : " + days + "d " + hours + "h "
                + minutes + "m " + seconds + "s ";

                    if (distance < 0) {
                        clearInterval(x);
                        $('input[type=radio]').removeAttr('required');
                        document.getElementById("timer").innerHTML = "END TEST ... ";
                        document.getElementById("quiz").submit(); 
                        // if(startTime != 1){
                        // } else{
                        //     document.getElementById("quiz").submit(disabled); 
                        //     window.location.href="{{ route('student.test.lesson',$lesson) }}";
                        // }
                    }
                }, 1000);
            }
        }
    });
}

})



// }
// else{
//     window.location.href="{{ route('student.test.lesson',$lesson) }}";
// }
</script>
@endpush
