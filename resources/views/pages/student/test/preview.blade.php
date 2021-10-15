@extends('app.app')
@push('add-style')
<style>
input[type=radio] {
    margin-top:15px;
    cursor: pointer;
}
span{
    cursor: pointer;
}
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="" method="post" id="quiz" name="quiz">
                    @csrf
                    
                    
                    @foreach ($answered as $no => $answer)
                    
                    @php
                        $data = '<span class="badge badge-danger">Belum Terjawab :( </span>';
                    @endphp
                    @if ($answer->correct != 'NULL')
                    @php
                        $data = '';
                    @endphp   
                    @endif 
                    @php
                        echo $data;
                    @endphp

                    
                    
                    <div class="d-block">
                        <div class="ask d-flex"><span>{{ $no+1 }}</span>.<span class="span">{!! $answer->question->ask !!}</span></div>
                            <div class="option mt-2">
                                <div class="form-group">
                                     <input disabled type="radio" value="A" class="" 
                                        @if ($answer->correct == 'A')
                                            checked
                                        @endif> 
                                        <span class="bagde 
                                        {{ ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'A' && $answer->correct == 'A') ? 'badge-success' : (($questions->where('id',$answer->question->id)->pluck('answer')->first() != 'A' && $answer->correct == 'A') ? 'badge-danger' : ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'A' &&  $questions->where('id',$answer->question->id)->pluck('answer')->first() != $answer->correct ? 'badge-success' : '')) }}">A. {{ $answer->question->option1 }}</span> <br>

                                        <input disabled type="radio" value="B" class="" 
                                        @if ($answer->correct == 'B')
                                            checked
                                        @endif
                                        >
                                        <span class="bagde {{ ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'B' && $answer->correct == 'B') ? 'badge-success' : (($questions->where('id',$answer->question->id)->pluck('answer')->first() != 'B' && $answer->correct == 'B') ? 'badge-danger' : ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'B' &&  $questions->where('id',$answer->question->id)->pluck('answer')->first() != $answer->correct ? 'badge-success' : '')) }}
                                            ">B. {{ $answer->question->option2 }}</span><br>
                                        

                                        <input disabled type="radio" value="C" class=""
                                        @if ($answer->correct == 'C')
                                            checked
                                        @endif
                                        >
                                        <span class="bagde {{ ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'C' && $answer->correct == 'C') ? 'badge-success' : (($questions->where('id',$answer->question->id)->pluck('answer')->first() != 'C' && $answer->correct == 'C') ? 'badge-danger' : ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'C' &&  $questions->where('id',$answer->question->id)->pluck('answer')->first() != $answer->correct ? 'badge-success' : '')) }}
                                            ">C. {{ $answer->question->option3 }}</span> <br>

                                        <input disabled type="radio" value="D" class=""
                                        @if ($answer->correct == 'D')
                                            checked
                                        @endif
                                        > 
                                        <span class="bagde {{ ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'D' && $answer->correct == 'D') ? 'badge-success' : (($questions->where('id',$answer->question->id)->pluck('answer')->first() != 'D' && $answer->correct == 'D') ? 'badge-danger' : ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'D' &&  $questions->where('id',$answer->question->id)->pluck('answer')->first() != $answer->correct ? 'badge-success' : '')) }}
                                            ">D. {{ $answer->question->option4 }}</span> <br>

                                        <input disabled type="radio" value="E" class=""
                                        @if ($answer->correct == 'E')
                                            checked
                                        @endif
                                        > 
                                        <span class="bagde  {{ ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'E' && $answer->correct == 'E') ? 'badge-success' : (($questions->where('id',$answer->question->id)->pluck('answer')->first() != 'E' && $answer->correct == 'E') ? 'badge-danger' : ($questions->where('id',$answer->question->id)->pluck('answer')->first() == 'E' &&  $questions->where('id',$answer->question->id)->pluck('answer')->first() != $answer->correct ? 'badge-success' : '')) }}
                                            ">E. {{ $answer->question->option5 }}</span>  <br>
                 
                                </div>
                            </div>
                    </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('add-script')
<script>
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