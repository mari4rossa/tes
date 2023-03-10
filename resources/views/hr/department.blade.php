<!DOCTYPE html>
<html>
    <head>
        <title>Divisi</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    </head>
    <body>
        <!--Navigation Bar-->
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link active border border-top-0 border-start-0 rounded" aria-current="page" href="/department">Divisi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/position">Jabatan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/employee">Karyawan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/mutation">Mutasi</a>
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
            <h1 style="text-align: center">Divisi</h1><br>

            <!-- button modal insert department -->
            <a class="btn btn-success" href="javascript:void(0)" id="createNewDepartment">Tambah Divisi</a>&nbsp;&nbsp;&nbsp;
            <!-- button export  -->
            <a class="btn btn-warning" href="{{ route('department.export') }}">Ekspor Data Divisi</a><br><br><br>
            <!-- button modal import department -->
            <form action="{{ route('department.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="file" name="file" class="form-control">
                <button class="btn btn-info" type="submit">Impor Data Divisi</button>
            </form><br><br><br>
            <!-- <a class="btn btn-info" href="javascript:void(0)" id="importDepartment">Impor Divisi</a> -->

            <!-- tabel department -->
            <table id="departmentTable" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Kode Divisi</th>
                        <th>Nama Divisi</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>

        <!-- Insert Department Modal -->
        <div class="modal fade" id="createDepartmentModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="insertModalHeading"></h4>
                        <button type="button" class="btn-close insert" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="departmentInsertForm" name="departmentInsertForm" class="form-horizontal">
                            <table>
                                <tr>
                                    <!-- department code -->
                                    <td>Kode Divisi: </td>
                                    <td class="ps-3">
                                        <input name="department_code" id="department_code" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr><tr>
                                    <!-- department name -->
                                    <td>Nama Divisi: </td>
                                    <td class="ps-3">
                                        <input name="department_name" id="department_name" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr>
                            </table>
                             <!-- button submit -->
                             <br>
                             <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary" id="insertBtn" value="create">Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Department Modal -->
        <div class="modal fade" id="updateDepartmentModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="updateModalHeading"></h4>
                        <button type="button" class="btn-close update" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="departmentUpdateForm" name="departmentUpdateForm" class="form-horizontal">
                            <input type="hidden" name="department_id" id="department_id">
                            <table>
                                <tr>
                                    <!-- department code -->
                                    <td>Kode Divisi: </td>
                                    <td class="ps-3">
                                        <input disabled name="department_code_update" id="department_code_update" oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr><tr>
                                    <!-- department name -->
                                    <td>Nama Divisi: </td>
                                    <td class="ps-3">
                                        <input name="department_name_update" id="department_name_update" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr>
                            </table>
                             <!-- button submit -->
                             <br>
                             <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary" id="updateBtn" value="update">Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </body>

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var departmentTable = $('#departmentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('department.department-table') }}",
                columns: [
                    {data: 'department_code', name: 'department_code'},
                    {data: 'department_name', name: 'department_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });

            $('#createNewDepartment').click(function () {
                $('#insertBtn').val("create-department");
                $('#departmentInsertForm').trigger("reset");
                $('#insertModalHeading').html("Input Divisi Baru");
                $('#createDepartmentModal').modal('show');
            });

            $('#insertBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#departmentInsertForm').serialize(),
                    url: "{{ route('department.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#departmentInsertForm').trigger("reset");
                            $('#createDepartmentModal').modal('hide');
                        }
                        departmentTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#insertBtn').html('Simpan');
                        alert('Error:'+data.message);

                    }
                });
            });

            $('body').on('click', '.editDepartment', function () {
                var department_id = $(this).data('id');
                $.get("{{ url('department') }}" +'/' + department_id +'/edit', function (data) {
                    $('#updateModalHeading').html("Edit Data Divisi");
                    $('#updateBtn').val("edit-department");
                    $('#updateDepartmentModal').modal('show');
                    $('#department_id').val(data.id);
                    $('#department_code_update').val(data.department_code);
                    $('#department_name_update').val(data.department_name);
                })
            });

            $('#updateBtn').click(function (e) {
                var department_id = $(this).data("id");
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#departmentUpdateForm').serialize(),
                    url: "{{ url('department') }}" +'/' + department_id,
                    type: "PUT",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#departmentUpdateForm').trigger("reset");
                            $('#updateDepartmentModal').modal('hide');
                        }
                        departmentTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#updateBtn').html('Simpan Perubahan');
                        alert('Error:'+data.message);
                    }
                });
            });

            $('body').on('click', '.deleteDepartment', function () {
                var department_id = $(this).data("id");
                var confirmation = confirm("Apa Anda yakin ingin menonaktifkan divisi ini?");

                if(confirmation){
                    $.ajax({
                        url: "{{ url('department') }}"+'/'+department_id,
                        method: "DELETE",
                        success: function (data) {
                            departmentTable.draw();
                            alert('Message: '+data.message);
                        },
                        error: function (data) {
                            alert('Error:'+data.message);
                        }
                    });
                }
            });

            $('.btn-close.insert').click(function () {
                $('#insertBtn').html('Simpan');
                $('#departmentInsertForm').trigger("reset");
                $('#createDepartmentModal').modal('hide');
            });

            $('.btn-close.update').click(function () {
                $('#insertBtn').html('Simpan Perubahan');
                $('#departmentUpdateForm').trigger("reset");
                $('#updateDepartmentModal').modal('hide');
            });

        });
    </script>
</html>