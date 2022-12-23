<!DOCTYPE html>
<html>
    <head>
        <title>Pelatihan Karyawan</title>
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
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/mutation">Mutasi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/trainer">Pelatih</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link border border-top-0 border-start-0 rounded" href="/training">Pelatihan</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active border border-top-0 border-start-0 rounded" aria-current="page" href="/training-history">Pelatihan Karyawan</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <br><br>
            <h1 style="text-align: center">Pelatihan Karyawan</h1><br>

            <!-- button import -->
            <!-- <form action="{{ route('training.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="file" name="file" class="form-control">
                <button class="btn btn-info" type="submit">Impor Data Pelatihan</button>
            </form> -->
            <!-- <br><br><br> -->

            
        </div>
        <div class="mx-5">
            <!-- button modal insert training -->
            <a class="btn btn-success" href="javascript:void(0)" id="createNewTrainingHistory">Tambah Pelatihan Karyawan</a>&nbsp;&nbsp;&nbsp;
            <!-- button export  -->
            <a class="btn btn-warning" href="{{ route('training-history.export') }}">Ekspor Data Pelatihan Karyawan</a><br><br><br>
            <!-- tabel training history -->
            <table id="trainingHistoryTable" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Divisi</th>
                        <th>Nama Program</th>
                        <th>Kode Pelatih</th>
                        <th>Nama Pelatih</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>  

        <!-- Insert Training History Modal -->
        <div class="modal fade" id="createTrainingHistoryModal" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="insertModalHeading"></h4>
                        <button type="button" class="btn-close insert" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="trainingHistoryInsertForm" name="trainingHistoryInsertForm" class="form-horizontal after-add-more">
                            <table>
                                <tr>
                                    <!-- employee id  -->
                                    <td>Karyawan:</td>
                                    <td class="ps-1">
                                        <select required name="employee_id" id="employee_id" class="form-select">
                                            <option value="0" selected disabled></option>
                                            @foreach($employees as $employee)
                                                <option value="{{$employee->id}}">{{$employee->nik}} - {{$employee->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <!-- training id  -->
                                    <td>&emsp;Pelatihan:</td>
                                    <td class="ps-1">
                                        <select required name="training_id" id="training_id" class="form-select">
                                            <option value="0" selected disabled></option>
                                            @foreach($trainings as $training)
                                                <option value="{{$training->id}}">{{$training->training_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <!-- start date  -->
                                    <td>&emsp;Tanggal mulai: </td>
                                    <td class="ps-1"><input name="start_date" id="start_date" class="form-control" required type="date" ></td>
                                    <!-- end date  -->
                                    <td>&emsp;Tanggal selesai: </td>
                                    <td class="ps-1"><input name="end_date" id="end_date" class="form-control" type="date" ></td>
                                </tr>
                            </table>
                             <br>
                             <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-success add-more" type="button">
                                    <i class="glyphicon glyphicon-plus"></i>Tambah</button>
                                    &emsp;
                            <button type="submit" class="btn btn-primary" id="insertBtn" value="create">Simpan</button>
                        </div>
                        </form>
                    </div>
                        <!-- button submit -->
                </div>
            </div>
        </div>

        <!-- copy insert invisible -->
        <div class="copy invisible">
            <form id="trainingHistoryInsertForm" name="trainingHistoryInsertForm" class="form-horizontal control-group">
                <table>
                    <tr>
                        <!-- employee id  -->
                        <td>Karyawan:</td>
                        <td class="ps-1">
                            <select required name="employee_id" id="employee_id" class="form-select">
                                <option value="0" selected disabled></option>
                                    @foreach($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->nik}} - {{$employee->name}}</option>
                                    @endforeach
                            </select>
                        </td>
                        <!-- training id  -->
                        <td>&emsp;Pelatihan:</td>
                        <td class="ps-1">
                            <select required name="training_id" id="training_id" class="form-select">
                                <option value="0" selected disabled></option>
                                     @foreach($trainings as $training)
                                        <option value="{{$training->id}}">{{$training->training_name}}</option>
                                    @endforeach
                            </select>
                        </td>
                        <!-- start date  -->
                        <td>&emsp;Tanggal mulai: </td>
                        <td class="ps-1"><input name="start_date" id="start_date" class="form-control" required type="date" ></td>
                        <!-- end date  -->
                        <td>&emsp;Tanggal selesai: </td>
                        <td class="ps-1"><input name="end_date" id="end_date" class="form-control" type="date" ></td>
                        <td>
                            <button class="btn btn-danger btn-sm remove" aria-label="Buang" type="button"><i class="glyphicon glyphicon-remove"></i>X</button><br><br>
                        </td>       
                    </tr>
                </table>
                <br>
            </form>
        </div>
        

        <!-- Update Training History Modal -->
        <div class="modal fade" id="updateTrainingHistoryModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="updateModalHeading"></h4>
                        <button type="button" class="btn-close update" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="trainingHistoryUpdateForm" name="trainingHistoryUpdateForm" class="form-horizontal">
                            <input type="hidden" name="training_history_id" id="training_history_id">
                            <table>
                                <tr>
                                    <!-- end date  -->
                                    <td>Tanggal selesai: </td>
                                    <td class="ps-3"><input required name="end_date_update" id="end_date_update" class="form-control" type="date" ></td>
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

            var trainingHistoryTable = $('#trainingHistoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('training-history.training-history-table') }}",
                columns: [
                    {data: 'nik', name: 'nik'},
                    {data: 'name', name: 'name'},
                    {data: 'position_name', name: 'position_name'},
                    {data: 'department_name', name: 'department_name'},
                    {data: 'training_name', name: 'training_name'},
                    {data: 'trainer_code', name: 'trainer_code'},
                    {data: 'trainer_name', name: 'trainer_name'},
                    {data: 'start_date', name: 'start_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )},
                    {data: 'end_date', name: 'end_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });

            $('#createNewTrainingHistory').click(function () {
                $('#insertBtn').val("create-training-history");
                $('#trainingHistoryInsertForm').trigger("reset");
                $('#insertModalHeading').html("Input Pelatihan Karyawan");
                $('#createTrainingHistoryModal').modal('show');
            });

            $('#insertBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Menyimpan..');
                var employeeId = [];
                $("input[name='employee_id']").each(function(){
                    employeeId.push($(this).val());
                    console.log(employeeId);
                });
                
                $.ajax({
                    data: $('#trainingHistoryInsertForm').serialize(),
                    url: "{{ route('training-history.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#trainingHistoryInsertForm').trigger("reset");
                            $('#createTrainingHistoryModal').modal('hide');
                        }
                        trainingHistoryTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#insertBtn').html('Simpan');
                        alert('Error:'+data.message);

                    }
                });
            });

            $('body').on('click', '.editTrainingHistory', function () {
                var training_history_id = $(this).data('id');
                $.get("{{ url('training-history') }}" +'/' + training_history_id +'/edit', function (data) {
                    $('#updateModalHeading').html("Input Tanggal Selesai");
                    $('#updateBtn').val("edit-training-history");
                    $('#updateTrainingHistoryModal').modal('show');
                    $('#training_history_id').val(data.id);
                    $('#end_date_update').val(data.end_date);
                })
            });

            $('#updateBtn').click(function (e) {
                var training_history_id = $(this).data("id");
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#trainingHistoryUpdateForm').serialize(),
                    url: "{{ url('training-history') }}" +'/' + training_history_id,
                    type: "PUT",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#trainingHistoryUpdateForm').trigger("reset");
                            $('#updateTrainingHistoryModal').modal('hide');
                        }
                        trainingHistoryTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#updateBtn').html('Simpan Perubahan');
                        alert('Error:'+data.message);
                    }
                });
            });

            $('.btn-close.insert').click(function () {
                $('#trainingHistoryInsertForm').trigger("reset");
                $('#createTrainingHistoryModal').modal('hide');
            });

            $('.btn-close.update').click(function () {
                $('#trainingHistoryUpdateForm').trigger("reset");
                $('#updateTrainingHistoryModal').modal('hide');
            });

            $(".add-more").click(function(){ 
                var html = $(".copy").html();
                $(".after-add-more").before(html);
            });

            // saat tombol remove dklik control group akan dihapus 
            $("body").on("click",".remove",function(){ 
                $(this).parents(".control-group").remove();
            });

        });
    </script>

</html>