<!DOCTYPE html>
<html>
    <head>
        <title>Profil Karyawan</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

        <!-- format tanggal  -->
        <script src="https://cdn.datatables.net/plug-ins/1.13.1/dataRender/datetime.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>

    </head>
    <body>
        <!--Navigation Bar-->
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link active border border-top-0 border-start-0 rounded" aria-current="page" href="/employee">Kembali</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <br><br>
            <h3 style="text-align: center">Profil Karyawan</h3><br>
            <br><br><br>  

        </div>

        <div class="mx-5">
            <p>Data pribadi</p>
            <!-- tabel employee -->
            <table id="employeeTable" class="table table-bordered data-table ">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Divisi</th>
                        <th>Status</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Keluar</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><br><br>

        <div class="mx-5">
            <p>Riwayat Mutasi</p>
            <!-- tabel mutation -->
            <table id="mutationTable" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Jabatan Lama</th>
                        <th>Divisi Lama</th>
                        <th>Jabatan Baru</th>
                        <th>Divisi Baru</th>
                        <th>Tanggal Mutasi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><br><br>

        <div class="mx-5">
            <p>Riwayat Pelatihan</p>
            <!-- tabel training history -->
            <table id="trainingHistoryTable" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Nama Program</th>
                        <th>Kode Pelatih</th>
                        <th>Nama Pelatih</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div> 

    </body>

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var nik = "{{$nik}}";

            var employeeTable = $('#employeeTable').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                "searching": false,
                ordering: false,
                info: false,
                ajax: {
                    url:"{{ route('employee-profile.employee-table') }}",
                    data: {"nik": nik}
                },
                columns: [
                    {data: 'nik', name: 'nik'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'position_name', name: 'position_name'},
                    {data: 'department_name', name: 'department_name'},
                    {data: 'active', name: 'active'},
                    {data: 'entry_date', name: 'entry_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )},
                    {data: 'out_date', name: 'out_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )}
                ],
            });

            var mutationTable = $('#mutationTable').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                "searching": false,
                ordering: false,
                info: false,
                ajax: {
                    url: "{{ route('employee-profile.mutation-table') }}",
                    data: {"nik": nik}
                },
                columns: [
                    {data: 'old_position', name: 'old_position'},
                    {data: 'old_department', name: 'old_department'},
                    {data: 'new_position', name: 'new_position'},
                    {data: 'new_department', name: 'new_department'},
                    {data: 'start_date', name: 'start_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )},
                ],
            });

            var trainingHistoryTable = $('#trainingHistoryTable').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                "searching": false,
                ordering: false,
                info: false,
                ajax: {
                    url: "{{ route('employee-profile.training-history-table') }}",
                    data: {"nik": nik}
                },
                columns: [
                    {data: 'training_name', name: 'training_name'},
                    {data: 'trainer_code', name: 'trainer_code'},
                    {data: 'trainer_name', name: 'trainer_name'},
                    {data: 'start_date', name: 'start_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )},
                    {data: 'end_date', name: 'end_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )}
                ],
            });

        });
    </script>

</html>