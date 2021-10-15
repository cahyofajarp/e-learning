<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <title>Export Pdf Siswa</title>
<style type="text/css">
*{
    /* font-family: 'Poppins', sans-serif; */
}
.bg-header{
    background: #159957;
    width: 100%;
    height: 150px;
    border-radius: 5px;
}
.bg-header h3{
    
    font-family: 'Poppins', sans-serif;
}
.bg-custom-blue{
        background: linear-gradient(to right,#2193b0,#6dd5ed);
        color:white;
}
.text-30{
    font-size: 30px;
}
.text-20{
    font-size: 20px;
}
.text-18{
    font-size: 18px;
}
.text-16{
    font-size: 16px;
}
.text-14{
    font-size: 14px;
}
.text-12{
    font-size: 12px;
}
.text-10{
    font-size: 10px;
}
table{
    
    font-family: 'Poppins', sans-serif;
}
.sub-txt{
    
    font-family: Arial, Helvetica, sans-serif;
}
</style>
</head>
<body>
<div class="container-fluid mt-1">
    <div class="bg-header p-5" style="color: white">
        <div class="d-block">
            <h3 class="text-20" style="text-align: left">Data File PDF</h3>
            <h3 class="text-30" style="text-align: right;margin-top:-25px">SMKN 24 JAKARTA</h3>
        </div>
        <div class="noted">
            <span class="">
                <small class="sub-txt d-block">Data ini terdapat didalam applikasi E-Learning SMK 24</small>
                <small class="sub-txt">Report Data tercatat pada tanggal : 12 Jan 2021 12:50</small>
            </span>
        </div>
    </div>
    <table class="table table-bordered mt-2" style="
    border-radius: 50px;">
        <thead class="">
            <tr>
                <th>#</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1 ?>
            @foreach ($students as $student)
                <tr>
                    <td class="text-center">{{ $no }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->registration_number }}</td>
                    <td>{{ $student->classroom->levelclass->name }}</td>
                    <td>{{ $student->classroom->name }}</td>
                    <td>{{ $student->address }}</td>
                </tr>
            
            <?php $no++ ?>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>