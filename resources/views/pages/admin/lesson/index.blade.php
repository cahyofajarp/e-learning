@extends('app.app')
@push('add-style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@endpush
@section('content')

{{-- 
<div class="modal" id="modalLevelclass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="background-color: white !important">
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
        <form method="post" action="" enctype="multipart/form-data" id="formDataUpdate" data-url="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <input type="hidden" value="" id="data-url">
                <div class="form-group">
                    <label for="">* Name</label> 
                    <small class="d-block mb-4 text-gray">Noted : Silahkan isi wajib angka ,contoh : 10 (Maksud nya adalah kelas 10) </small>
                            
                    <input type="text" name="name" class="form-control" id="name" value="">
                     <span class="text-danger error-text name_error" style="font-size:13px"></span>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-update" style="letter-spacing: 3px">UPDATE</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <form method="post" action="" id="formDataCreate" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Nama Kelas</label>
                    <span class="text-gray d-block mb-1"><small>* Contoh : Rekaya Perangkat Lunak</small></span>
                    <input type="text" name="name" class="form-control">
                    <span class="text-danger error-text name_error" style="font-size:13px"></span>
                </div> 
                <div class="form-group">
                    <label for="">Sub Kelas</label>
                    <span class="text-gray d-block mb-1"><small>* Contoh : 1 (Sama dengan Rekayasa Perangkat Lunak 1)</small></span>
                    <input type="number" name="sub_class" class="form-control">
                    <span class="text-danger error-text sub_class_error" style="font-size:13px"></span>
                </div>
                <div class="form-group">
                    <label for="">Pilih LevelClass</label>
                    <span class="text-gray d-block mb-1"><small>* Pilih levelclass Contoh : 10 (Sama dengan 10 Rekayasa Perangkat Lunak 1)</small></span>
                    <select name="levelclass_id" class="form-control" id="select2">
                        <option value="">---</option>
                        @foreach ($levelclasses as $levelclass)
                            <option value="{{ $levelclass->id }}">{{ $levelclass->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text levelclass_id_error" style="font-size:13px"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-custom-green btn-block" id="btn-create" style="letter-spacing: 3px">CREATE</button>
            </div>
        </form>
      </div>
    </div>
</div> --}}

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-hover table-striped" id="myTable">
                        <thead class="btn-custom-green-blue text-white">
                            <tr>
                                <th  style="width: 3%">No</th>
                                <th>Level Class</th>
                                <th>Nama Jurusan + Kelas</th>
                                <th>Status</th>
                                <th>Jumlah Pelajaran</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;  ?>
                            @foreach ($classrooms->sortBy('name') as $classroom)
                                <tr>    
                                    <td class="text-center">{{ $no }}</td>
                                    <td>{{ $classroom->levelclass->name }}</td>
                                    <td>{{ $classroom->name }}</td>
                                    <td>
                                        @if ($classroom->lessons()->count() > 0)
                                            <i class="mdi mdi-checkbox-marked-outline" style="color:green"></i>
                                        @else
                                            <i class="mdi mdi-alert-circle" style="color: orangered"></i>
                                        @endif
                                    </td>
                                    <td>
                                        {{$classroom->lessons()->count()}} Pelajaran
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.lesson.classroomLesson',$classroom) }}" class="btn-custom-green btn btn-sm"> <i class="mdi mdi-eye"></i> Lihat Mapel</a>
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

<script>
    $(document).ready(function() {
        var t = $('#myTable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]
        } );
    
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });

    

</script>
@endpush