<!DOCTYPE html>
<html>
    <head>
        <title>Mutasi Karyawan</title>
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
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/department">Divisi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/position">Jabatan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/employee">Karyawan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active border border-top-0 border-start-0 rounded" aria-current="page" href="/mutation">Mutasi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/trainer">Pelatih</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/training">Pelatihan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/training-history">Pelatihan Karyawan</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <br><br>
            <h1 style="text-align: center">Mutasi Karyawan</h1><br>

            

        </div>

        <div class="mx-5">
            <!-- button export  -->
            <a class="btn btn-warning" href="{{ route('mutation.export') }}">Ekspor Data Mutasi</a>
            <br><br><br>
            <!-- tabel employee -->
            <table id="mutationTable" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Email</th>
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
        </div>

    </body>

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var mutationTable = $('#mutationTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('mutation.mutation-table') }}",
                columns: [
                    {data: 'nik', name: 'nik'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'old_position', name: 'old_position'},
                    {data: 'old_department', name: 'old_department'},
                    {data: 'new_position', name: 'new_position'},
                    {data: 'new_department', name: 'new_department'},
                    {data: 'start_date', name: 'start_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )},
                ],
            });

        });
    </script>


</html>