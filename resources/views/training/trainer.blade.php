<!DOCTYPE html>
<html>
    <head>
        <title>Pelatih</title>
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
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/position">Jabatan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/employee">Karyawan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/mutation">Mutasi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active border border-top-0 border-start-0 rounded" aria-current="page" href="/trainer">Pelatih</a>
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
            <h1 style="text-align: center">Pelatih</h1><br>

            <!-- button modal insert trainer -->
            <a class="btn btn-success" href="javascript:void(0)" id="createNewTrainer">Tambah Pelatih</a>&nbsp;&nbsp;&nbsp;
            <!-- button export  -->
            <a class="btn btn-warning" href="{{ route('trainer.export') }}">Ekspor Data Pelatih</a><br><br><br>
            <!-- button modal import department -->
            <form action="{{ route('trainer.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="file" name="file" class="form-control">
                <button class="btn btn-info" type="submit">Impor Data Pelatih</button>
            </form>
            <br><br><br>

            <!-- tabel trainer -->
            <table id="trainerTable" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>

        <!-- Insert Trainer Modal -->
        <div class="modal fade" id="createTrainerModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="insertModalHeading"></h4>
                        <button type="button" class="btn-close insert" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="trainerInsertForm" name="trainerInsertForm" class="form-horizontal">
                            <table>
                                <tr>
                                    <!-- trainer code -->
                                    <td>Kode: </td>
                                    <td class="ps-3">
                                        <input name="trainer_code" id="trainer_code" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- trainer name -->
                                    <td>Nama: </td>
                                    <td class="ps-3">
                                        <input name="trainer_name" id="trainer_name" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- trainer email -->
                                    <td>Email: </td>
                                    <td class="ps-3">
                                        <input type="email" name="trainer_email" id="trainer_email" required oninput="this.value = this.value.toUpperCase()" class="form-control" value="">
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

        <!-- Update Trainer Modal -->
        <div class="modal fade" id="updateTrainerModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="updateModalHeading"></h4>
                        <button type="button" class="btn-close update" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="trainerUpdateForm" name="trainerUpdateForm" class="form-horizontal">
                            <input type="hidden" name="trainer_id" id="trainer_id">
                            <table>
                                <tr>
                                    <!-- trainer code -->
                                    <td>Kode: </td>
                                    <td class="ps-3">
                                        <input disabled name="trainer_code_update" id="trainer_code_update" oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- trainer name -->
                                    <td>Nama: </td>
                                    <td class="ps-3">
                                        <input name="trainer_name_update" id="trainer_name_update" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- trainer email -->
                                    <td>Email: </td>
                                    <td class="ps-3">
                                        <input name="trainer_email_update" id="trainer_email_update" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="email" value="">
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

            var trainerTable = $('#trainerTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainer.trainer-table') }}",
                columns: [
                    {data: 'trainer_code', name: 'trainer_code'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });

            $('#createNewTrainer').click(function () {
                $('#insertBtn').val("create-trainer");
                $('#trainerInsertForm').trigger("reset");
                $('#insertModalHeading').html("Input Pelatih Baru");
                $('#createTrainerModal').modal('show');
            });

            $('#insertBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#trainerInsertForm').serialize(),
                    url: "{{ route('trainer.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#trainerInsertForm').trigger("reset");
                            $('#createTrainerModal').modal('hide');
                        }
                        trainerTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#insertBtn').html('Simpan');
                        alert('Error:'+data.message);

                    }
                });
            });

            $('body').on('click', '.editTrainer', function () {
                var trainer_id = $(this).data('id');
                $.get("{{ url('trainer') }}" +'/' + trainer_id +'/edit', function (data) {
                    $('#updateModalHeading').html("Edit Data Pelatih");
                    $('#updateBtn').val("edit-trainer");
                    $('#updateTrainerModal').modal('show');
                    $('#trainer_id').val(data.id);
                    $('#trainer_code_update').val(data.trainer_code);
                    $('#trainer_name_update').val(data.name);
                    $('#trainer_email_update').val(data.email);
                })
            });

            $('#updateBtn').click(function (e) {
                // var trainer_id = $(this).data("id");
                var trainer_id = $('#trainer_id').val();
                // console.log(trainer_id);
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#trainerUpdateForm').serialize(),
                    url: "{{ url('trainer') }}" +'/' + trainer_id,
                    type: "PUT",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#trainerUpdateForm').trigger("reset");
                            $('#updateTrainerModal').modal('hide');
                        }
                        trainerTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#updateBtn').html('Simpan Perubahan');
                        alert('Error:'+data.message);
                    }
                });
            });

            $('body').on('click', '.deleteTrainer', function () {
                var trainer_id = $(this).data("id");
                var confirmation = confirm("Apa Anda yakin ingin menonaktifkan pelatih ini?");

                if(confirmation){
                    $.ajax({
                        url: "{{ url('trainer') }}"+'/'+trainer_id,
                        method: "DELETE",
                        success: function (data) {
                            trainerTable.draw();
                            alert('Message: '+data.message);
                        },
                        error: function (data) {
                            alert('Error:'+data.message);
                        }
                    });
                }
            });

            $('.btn-close.insert').click(function () {
                $('#trainerInsertForm').trigger("reset");
                $('#createTrainerModal').modal('hide');
            });

            $('.btn-close.update').click(function () {
                $('#trainerUpdateForm').trigger("reset");
                $('#updateTrainerModal').modal('hide');
            });

        });
    </script>
</html>