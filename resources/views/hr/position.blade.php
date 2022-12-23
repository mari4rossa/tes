<!DOCTYPE html>
<html>
    <head>
        <title>Jabatan</title>
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
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/department">Divisi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active border border-top-0 border-start-0 rounded" aria-current="page" href="/position">Jabatan</a>
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
            <h1 style="text-align: center">Jabatan</h1><br>

            <!-- button modal insert department -->
            <a class="btn btn-success" href="javascript:void(0)" id="createNewPosition">Tambah Jabatan</a>&nbsp;&nbsp;&nbsp;
            <!-- button export  -->
            <a class="btn btn-warning" href="{{ route('position.export') }}">Ekspor Data Jabatan</a><br><br><br>
            <!-- button modal import position -->
            <form action="{{ route('position.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="file" name="file" class="form-control">
                <button class="btn btn-info" type="submit">Impor Data Jabatan</button>
            </form>
            
            <br><br><br>

            <!-- tabel position -->
            <table id="positionTable" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Jabatan</th>
                        <th>Kode Divisi</th>
                        <th>Nama Divisi</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <!-- Insert Position Modal -->
        <div class="modal fade" id="createPositionModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="insertModalHeading"></h4>
                        <button type="button" class="btn-close insert" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="positionInsertForm" name="positionInsertForm" class="form-horizontal">
                            <table>
                                <tr>
                                    <!-- position name -->
                                    <td>Jabatan: </td>
                                    <td class="ps-3">
                                        <input name="position_name" id="position_name" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- department id -->
                                    <td>Divisi: </td>
                                    <td class="ps-3">
                                        <select required="" name="department_id" id="department_id" class="form-select">
                                            <option value="0" selected disabled></option>
                                            @foreach($departments as $department)
                                                <option value="{{$department->id}}">{{$department->department_code}} - {{$department->department_name}}</option>
                                            @endforeach
                                        </select>
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

        <!-- Update Position Modal -->
        <div class="modal fade" id="updatePositionModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="updateModalHeading"></h4>
                        <button type="button" class="btn-close update" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="positionUpdateForm" name="positionUpdateForm" class="form-horizontal">
                            <input type="hidden" name="position_id" id="position_id">
                            <table>
                                <tr>
                                    <!-- position name -->
                                    <td>Jabatan: </td>
                                    <td class="ps-3">
                                        <input name="position_name_update" id="position_name_update" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- department id -->
                                    <td>Divisi: </td>
                                    <td class="ps-3">
                                        <select required="" name="department_id_update" id="department_id_update" class="form-select">
                                            @foreach($departments as $department)
                                                <option value="{{$department->id}}">{{$department->department_code}} - {{$department->department_name}}</option>
                                            @endforeach
                                        </select>
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

            var positionTable = $('#positionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('position.position-table') }}",
                columns: [
                    {data: 'position_name', name: 'position_name'},
                    {data: 'department_code', name: 'department_code'},
                    {data: 'department_name', name: 'department_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });

            $('#createNewPosition').click(function () {
                $('#insertBtn').val("create-position");
                $('#positionInsertForm').trigger("reset");
                $('#insertModalHeading').html("Input Jabatan Baru");
                $('#createPositionModal').modal('show');
            });

            $('#insertBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#positionInsertForm').serialize(),
                    url: "{{ route('position.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#positionInsertForm').trigger("reset");
                            $('#createPositionModal').modal('hide');
                        }
                        positionTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#insertBtn').html('Simpan');
                        alert('Error:'+data.message);

                    }
                });
            });

            $('body').on('click', '.editPosition', function () {
                var position_id = $(this).data('id');
                $.get("{{ url('position') }}" +'/' + position_id +'/edit', function (data) {
                    $('#updateModalHeading').html("Edit Data Jabatan");
                    $('#updateBtn').val("edit-position");
                    $('#updatePositionModal').modal('show');
                    $('#position_id').val(data.id);
                    $('#position_name_update').val(data.position_name);
                    $('#department_id_update').val(data.department_id).change();
                })
            });

            $('#updateBtn').click(function (e) {
                var position_id = $(this).data("id");
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#positionUpdateForm').serialize(),
                    url: "{{ url('position') }}" +'/' + position_id,
                    type: "PUT",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#positionUpdateForm').trigger("reset");
                            $('#updatePositionModal').modal('hide');
                        }
                        positionTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#updateBtn').html('Simpan Perubahan');
                        alert('Error:'+data.message);
                    }
                });
            });

            $('body').on('click', '.deletePosition', function () {
                var position_id = $(this).data("id");
                var confirmation = confirm("Apa Anda yakin ingin menonaktifkan jabatan ini?");

                if(confirmation){
                    $.ajax({
                        url: "{{ url('position') }}"+'/'+position_id,
                        method: "DELETE",
                        success: function (data) {
                            positionTable.draw();
                            alert('Message: '+data.message);
                        },
                        error: function (data) {
                            alert('Error:'+data.message);
                        }
                    });
                }
            });

            $('.btn-close.insert').click(function () {
                $('#positionInsertForm').trigger("reset");
                $('#createPositionModal').modal('hide');
            });

            $('.btn-close.update').click(function () {
                $('#positionUpdateForm').trigger("reset");
                $('#updatePositionModal').modal('hide');
            });

        });
    </script>
</html>