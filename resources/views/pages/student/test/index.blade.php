@extends('app.app')

@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">


@endpush
@section('content')

<div class="modal fade" id="modalCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content p-2" style="background-color: white !important">
        <div class="modal-header">
            <img src="{{ asset('images/6663-min.jpg') }}" alt="" style="  
            width: 70%;
            margin: 0 auto;
            margin-right: -10px;
        ">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <form method="post" action="" id="formData" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="data-redirect" value="">
                    <label for="">Code Test</label>
                    <span class="text-gray d-block mb-1"><small>* Noted: Silahkan masukan code Ujian yang sudah di bagikan guru.</small></span>
                    <input type="password" name="code" class="form-control">
                    <span class="text-danger error-text code_error" style="font-size:13px"></span>
                </div> 
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn alert-success btn-block" id="btn-create-test" style="letter-spacing: 3px">CHECK CODE</button>
            </div>
        </form>
      </div>
    </div>
</div> 

<nav aria-label="Page breadcrumb">
    <ol class="breadcrumb" style="background: white">
        <li class="breadcrumb-item" aria-current="page">
            <a class="text-gray" style="color:#6c757d" href="{{ route('admin.home') }}"><i class="mdi mdi-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                Materi
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#6c757d" href="">
                {{ auth()->user()->student->classroom->levelclass->name }} {{ auth()->user()->student->classroom->name }}
            </a>
        </li>

        <li class="breadcrumb-item">
            <a class="text-gray" style="color:#000" href="">
               {{ $lesson->name }}
            </a>
        </li>

    </ol>
</nav>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
             
                <div class="table-responsive">
                    <table class="table table-striped" id="myTable" style="width:190%">
                        <thead class="alert-info">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th >Title Ujian</th>
                                <th>Description</th>
                                <th>Nilai KKM</th>
                                <th>Waktu Ujian (Menit)</th>
                                <th>Waktu Test Dimulai</th>
                                <th>Batas Test Selesai</th>
                                <th>Status</th>
                                <th style="width:10%">Created By</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($classroom->tests as $test)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $test->name }} </td>
                                        <td>{{ $test->decription }}</td>
                                        <td>{{ $test->standard }}</td>
                                        <td>{{ $test->time }} Menit</td>
                                        <td>{{ $test->start_test }} </td>
                                        <td>{{ $test->deadline_test }}  </td>
                                        <td>
                                            @php
                                                $status = 'Belum Dimulai';
                                                $color = 'danger';
                                                $icon = 'mdi mdi-clock';

                                            @endphp
                                            @if ($test->status == 1)
                                                @php    
                                                    $status = 'Test Sedang Dimulai';
                                                    $color = 'warning';
                                                    $icon = 'mdi  mdi-clipboard-text';
                                                @endphp
                                            @elseif ($test->status == 2) 
                                                @php    
                                                    $status = 'Test Sudah Berakhir';
                                                    $color = 'success';
                                                    $icon = 'mdi mdi-clipboard-check';
                                                @endphp
                                            @endif
                                            <span class="btn btn-{{ $color }} px-4 py-2" style="border-radius: 100px"> <i class="{{ $icon }}"></i> {{ $status }}</span>
                                        </td>
                                        <td>{{ $test->teacher->name }}</td>
                                        <td>
                                           @if ($result->where('test_id',$test->id)->first())

                                                @if ($result->where('test_id',$test->id)->first()->score != NULL)
                                                
                                                    <a href="{{ route('student.test.startTest.result',[$lesson,$classroom,$test,$result->where('test_id',$test->id)->first()]) }}" class="btn btn-info"><i class="mdi mdi-clipboard-check"></i> Lihat Hasil</a>
                                                @elseif($result->where('test_id',$test->id)->first()->start_test !== NULL && $result->where('test_id',$test->id)->first()->status == NULL)
                                                    <a href="{{ route('student.test.startTest.start',[$lesson,$classroom,$test]) }}" class="btn alert-info"><i class="mdi mdi-play"></i> Lanjutkan</a>
                                                @endif

                                                
                                           @elseif($test->status == 2)
                                                
                                                <a href="{{ route('student.test.startTest.result',[$lesson,$classroom,$test,$result->where('test_id',$test->id)->first()]) }}" class="btn btn-info"><i class="mdi mdi-clipboard-check"></i> Lihat Hasil</a>
                                            
                                            @elseif($test->status == 1 )                                 
                                         
                                                <button id="btn-test" data-check="{{ route('student.test.checkCode',[$lesson,$classroom,$test]) }}" data-redirect="{{ route('student.test.startTest',[$lesson,$classroom,$test]) }}"  data-toggle="modal" data-target="#modalCode" class="btn btn-custom-green"> <i class="mdi  mdi-play-circle"></i> Start Test</button>
                                         
                                            @else

                                                <button class="btn btn-warning"><i class="mdi mdi-clock"></i> Test Belum Dimulai</button>
                                            
                                            @endif
                                        </td>
                                    </tr>
                                <?php $no++; ?>
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
        var t = $('#myTable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": [0]
            } ],
            "order": []
        } );
    
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });

  

$('button#btn-test').click(function() {
    $('#modalCode').modal('show');
    let data = $(this).data('check');
    let redirect = $(this).data('redirect');
    
    $('form').trigger("reset");

    $('#formData').attr('action',data);

    $('#data-redirect').val(redirect);

    
});

$('#formData').submit(function(e) {
    let checkIfError = null;
    e.preventDefault();
    var formData = new FormData(this);

    let url = $(this).attr('action')
    let redirect = $('#data-redirect').val();

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: url,
        beforeSend:function(){
            $('#btn-create-test').addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-create-test').removeClass("disabled").html("CHECK CODE").attr('disabled',false);
            
        },
        success: function(res){

            if(res.success == true){     
                Swal.fire(
                'Success!',
                'Code berhasil,Silahkan buat test sekarang :D',
                'success'
                ) 
    
                $('#modalCode').modal('hide');
                $('form').trigger("reset");
                
                // testMiddleware();

                setTimeout(function() {
                    window.location.href = redirect;
                },500)
            }
            else{ 
                
                Swal.fire(
                'Error!',
                'Oops, Your code is false :( ,Please Contact Your Teacher.',
                'error'
                ) 
                $('.code_error').text('Oops, Your code is false :( , Please Contact Your Teacher.');
            }
        },
        error(err){
            console.log(err);
            checkIfError = true;
            if(checkIfError == true){
                $.each(err.responseJSON.errors,function(prefix,val) {
                    $('.'+prefix+'_error').text(val[0]);
                })
            }

        }

    })
    
});

</script>
@endpush