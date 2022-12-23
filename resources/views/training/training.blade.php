<!DOCTYPE html>
<html>
    <head>
        <title>Program Pelatihan</title>
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
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/trainer">Pelatih</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active border border-top-0 border-start-0 rounded" aria-current="page" href="/training">Pelatihan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/training-history">Pelatihan Karyawan</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <br><br>
            <h1 style="text-align: center">Program Pelatihan</h1><br>

            <!-- button modal insert training -->
            <a class="btn btn-success" href="javascript:void(0)" id="createNewTraining">Tambah Pelatihan</a>&nbsp;&nbsp;&nbsp;
            <!-- button export  -->
            <a class="btn btn-warning" href="{{ route('training.export') }}">Ekspor Data Pelatihan</a><br><br><br>
            <!-- button import -->
            <form action="{{ route('training.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="file" name="file" class="form-control">
                <button class="btn btn-info" type="submit">Impor Data Pelatihan</button>
            </form>
            <br><br><br>

            <!-- tabel trainer -->
            <table id="trainingTable" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Nama Program</th>
                        <th>Kode Pelatih</th>
                        <th>Nama Pelatih</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>

        <!-- Insert Training Modal -->
        <div class="modal fade" id="createTrainingModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="insertModalHeading"></h4>
                        <button type="button" class="btn-close insert" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="trainingInsertForm" name="trainingInsertForm" class="form-horizontal">
                            <table>
                                <tr>
                                    <!-- training name -->
                                    <td>Nama: </td>
                                    <td class="ps-3">
                                        <input required name="training_name" id="training_name" oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="" minlength="5" maxlength="180">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- trainer id -->
                                    <td>Pelatih: </td>
                                    <td class="ps-3">
                                        <select required name="trainer_id" id="trainer_id" class="form-select">
                                            <option value="0" selected disabled></option>
                                            @foreach($trainers as $trainer)
                                                <option value="{{$trainer->id}}">{{$trainer->trainer_code}} - {{$trainer->name}}</option>
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

        <!-- Update Training Modal -->
        <div class="modal fade" id="updateTrainingModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="updateModalHeading"></h4>
                        <button type="button" class="btn-close update" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="trainingUpdateForm" name="trainingUpdateForm" class="form-horizontal">
                            <input type="hidden" name="training_id" id="training_id">
                            <table>
                                <tr>
                                    <!-- training name -->
                                    <td>Nama: </td>
                                    <td class="ps-3">
                                        <input name="training_name_update" id="training_name_update" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="" minlength="5" maxlength="180">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- trainer id -->
                                    <td>Pelatih: </td>
                                    <td class="ps-3">
                                        <select required="" name="trainer_id_update" id="trainer_id_update" class="form-select">
                                            <option value="0" selected disabled></option>
                                            @foreach($trainers as $trainer)
                                                <option value="{{$trainer->id}}">{{$trainer->trainer_code}} - {{$trainer->name}}</option>
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

            var trainingTable = $('#trainingTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('training.training-table') }}",
                columns: [
                    {data: 'training_name', name: 'training_name'},
                    {data: 'trainer_code', name: 'trainer_code'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });

            $('#createNewTraining').click(function () {
                $('#insertBtn').val("create-training");
                $('#trainingInsertForm').trigger("reset");
                $('#insertModalHeading').html("Input Pelatihan Baru");
                $('#createTrainingModal').modal('show');
            });

            $('#insertBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#trainingInsertForm').serialize(),
                    url: "{{ route('training.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#trainingInsertForm').trigger("reset");
                            $('#createTrainingModal').modal('hide');
                        }
                        trainingTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#insertBtn').html('Simpan');
                        alert('Error:'+data.message);

                    }
                });
            });

            $('body').on('click', '.editTraining', function () {
                var training_id = $(this).data('id');
                $.get("{{ url('training') }}" +'/' + training_id +'/edit', function (data) {
                    $('#updateModalHeading').html("Edit Data Pelatihan");
                    $('#updateBtn').val("edit-training");
                    $('#updateTrainingModal').modal('show');
                    $('#training_id').val(data.id);
                    $('#training_name_update').val(data.training_name);
                    $('#trainer_id_update').val(data.trainer_id).change();
                })
            });

            $('#updateBtn').click(function (e) {
                var training_id = $(this).data("id");
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#trainingUpdateForm').serialize(),
                    url: "{{ url('training') }}" +'/' + training_id,
                    type: "PUT",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#trainingUpdateForm').trigger("reset");
                            $('#updateTrainingModal').modal('hide');
                        }
                        trainingTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#updateBtn').html('Simpan Perubahan');
                        alert('Error:'+data.message);
                    }
                });
            });

            $('body').on('click', '.deleteTraining', function () {
                var training_id = $(this).data("id");
                var confirmation = confirm("Apa Anda yakin ingin menonaktifkan pelatihan ini?");

                if(confirmation){
                    $.ajax({
                        url: "{{ url('training') }}"+'/'+training_id,
                        method: "DELETE",
                        success: function (data) {
                            trainingTable.draw();
                            alert('Message: '+data.message);
                        },
                        error: function (data) {
                            alert('Error:'+data.message);
                        }
                    });
                }
            });

            $('.btn-close.insert').click(function () {
                $('#trainingInsertForm').trigger("reset");
                $('#createTrainingModal').modal('hide');
            });

            $('.btn-close.update').click(function () {
                $('#trainingUpdateForm').trigger("reset");
                $('#updateTrainingModal').modal('hide');
            });

        });
    </script>
</html>