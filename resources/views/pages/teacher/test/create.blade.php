@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')


<nav aria-label="Page breadcrumb">
    <ol class="breadcrumb" style="background: white">
        <li class="breadcrumb-item" aria-current="page">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.home') }}"><i class="mdi mdi-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                Learning Management
            </a>
        </li> 
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.test.test',$classroom) }}">
                Test Management
            </a>
        </li> 
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.test.testCreate',[$classroom,$lesson]) }}">
                {{ $classroom->levelclass->name }} {{ $classroom->name }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                {{ $lesson->name }}
            </a>
        </li>
        <li class="breadcrumb-item" style="color:black">
            Create
        </li>

    </ol>
</nav>

<form action="{{ route('teacher.lesson.test.store',[$classroom,$lesson]) }}" method="post" id="formData">
    @csrf
    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Tambah Ujian</h4>
                    <small>* Noted : ujian ini akun masuk ke data ujian silahkan isi pertanyaan pada tombol biru di halaman awal.</small>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">  
                            <div class="form-group">
                                <label for="">* Mata Ujian</label><br>
                                <label for=""><small class="text-gray">* Noted : pilih mata pelajaran untuk di ujikan.</small></label>
                                <input type="text" readonly class="form-control" value="{{ $lesson->name }}"> 
                                <input type="hidden" class="form-control" value="{{ $lesson->id }}" name="lesson_id"> 
                                <span class="text-danger error-text lesson_id_error" style="font-size:13px"></span>
                            </div> 
                            <div class="form-group">
                                <label for="">* Code Test</label>
                                <input type="text" name="test_code" class="form-control">
                                <span class="text-danger error-text test_code_error" style="font-size:13px"></span>
                            </div> 
                            <div class="form-group">
                                <label for="">* Nama Ujian</label>
                                <input type="text" name="name" class="form-control">
                                <span class="text-danger error-text name_error" style="font-size:13px"></span>
                            </div> 
                                <div class="form-group">
                                    <label for="">* Nilai KKM</label>
                                    <input type="number" name="standard" class="form-control">
                                    <span class="text-danger error-text standard_error" style="font-size:13px"></span>
                                </div> 
                                <div class="form-group">
                                    <label for="">* Waktu Ujian (Satuan Menit)</label><br>
                                    <label for=""><small class="text-gray">* Noted : wajib menggunakan angka, contoh : 60 (sama dengan 60 menit)</small></label>
                                    <input type="number" name="time" class="form-control">
                                    <span class="text-danger error-text time_error" style="font-size:13px"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">* Deskripsi (Opsional)</label><br>
                                    <input type="text" name="decription" class="form-control">
                                    <span class="text-danger error-text decription_error" style="font-size:13px"></span>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <small>Optional (Boleh ditambahkan atau tidak)</small> <br>
                    <small>Tambahkan juga test ke kelas : </small>
                    <hr>
                    
                    <div class="form-group">
                        <select name="add_class_test[]" id="" class="form-control select2" multiple>
                            <option value="">---</option>
                            @foreach ($teacherLesson as $cl)
                                @if ($cl->classroom->id != $classroom->id)    
                                    <option value="{{ $cl->classroom->id }}">{{ $cl->classroom->name }}</option>
                                @endif
                            @endforeach
                            {{-- @foreach ($teacherLesson as $classroom) --}}
                                {{-- {{ $classroom }} --}}
                            {{-- @endforeach --}}
                            {{-- @foreach ($levelclasses as $levelclass)
                                @foreach ($levelclass->classrooms as $class)
                                    @if ($class->name != $classroom->name)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endif
                                @endforeach --}}
                            {{-- @endforeach --}}
                        </select>
                    </div>
                    <small class="text-gray mb-5"><b>Noted : Jadwal ujian akan terbuat setelah anda membuat soal.</b></small>
                    <button id="btn-create" type="submit" class="btn-block btn btn-custom-green mt-3">CREATE</button>
                        
                </div>
            </div>
        </div>
    </div>

</form>
@endsection

@push('add-script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>

<script>
 $(document).ready(function() {
    $('.select2').select2({
        theme: "bootstrap"
    });
});

// function testMiddleware(){
//     let getData = localStorage.getItem('data');

//     $.get('/admin/test/{{ $classroom->slug }}/classroom/lesson/{{ $lesson->slug }}/testcode/'+getData,function(data){
//         if(data.data != true){
//             window.location.href = '{{ route("admin.test.test",$classroom) }}';
//         }
//     })  

//     if(getData == '' || getData != '{{ $lesson->code }}'){ 
//         window.location.href = '{{ route("admin.test.test",$classroom) }}';
        
//     }  
// }
// testMiddleware();

$('#formData').submit(function(e) {
        e.preventDefault();
        let url = $(this).attr('action');
        let formData = new FormData(this);    
        
        $.ajax({
            type: 'POST',
            data : formData,
            contentType: false,
            processData: false,
            url: url,
            beforeSend:function(){
                $('#btn-create').addClass("disabled").html("<i class='mdi mdi-spin mdi-loading'></i>  Processing...").attr('disabled',true);
                $(document).find('span.error-text').text('');
            },    
            complete: function(){
                $('#btn-create').removeClass("disabled").html("CREATE").attr('disabled',false);
                
            },
            success: function(res){
                // $("#count").text(res.count);
                console.log(res);
                if(res.success == true){
                    Swal.fire(
                    'Success!',
                    'Ujian Berhasil Dibuat! Silahkan check email anda untuk melihat lebih detail',
                    'success'
                    )   

                    window.location.href = "{{ route('teacher.lesson.test.index',[$classroom,$lesson]) }}";

                }
                
            },
            error(err){
                $.each(err.responseJSON.errors,function(prefix,val) {
                    $('.'+prefix+'_error').text(val[0]);
                })
            }

        })
})
</script>
@endpush