<!DOCTYPE html>
<html>
    <head>
        <title>Karyawan</title>
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
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->

        <style type="text/css">

            .loading {
                z-index: 9999;
                position: absolute;
                top: 0;
                left:-5px;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.4);
            }

            .loading-content {
                position: absolute;
                border: 16px solid #f3f3f3;
                border-top: 16px solid #3498db;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                top: 40%;
                left:50%;
                animation: spin 2s linear infinite;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
        </style>

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
                    <a class="nav-link active border border-top-0 border-start-0 rounded" aria-current="page" href="/employee">Karyawan</a>
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
            <h1 style="text-align: center">Karyawan</h1><br>
            
            <section id="loading">
                <div id="loading-content"></div>
            </section>
            @yield('content')
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

        </div>

        <div class="mx-5">
            <!-- button modal insert employee -->
            <a class="btn btn-success" href="javascript:void(0)" id="createNewEmployee">Tambah Karyawan</a>&nbsp;&nbsp;&nbsp;
            <!-- button export  -->
            <a class="btn btn-warning" href="{{ route('employee.export') }}">Ekspor Data Karyawan</a><br><br><br>
            <!-- button modal import position -->
            <form action="{{ route('employee.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="file" name="file" class="form-control">
                <button class="btn btn-info" type="submit">Impor Data Karyawan</button>
            </form>
            
            <br><br><br>  
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
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        

        <!-- Insert Employee Modal -->
        <div class="modal fade" id="createEmployeeModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="insertModalHeading"></h4>
                        <button type="button" class="btn-close insert" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="employeeInsertForm" name="employeeInsertForm" class="form-horizontal">
                            <table>
                                <tr>
                                    <!-- nik -->
                                    <td>NIK: </td>
                                    <td class="ps-3">
                                        <input name="nik" id="nik" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="" maxlength="8">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- name -->
                                    <td>Nama: </td>
                                    <td class="ps-3">
                                        <input name="name" id="name" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="" maxlength="50">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- email -->
                                    <td>Email: </td>
                                    <td class="ps-3">
                                        <input name="email" id="email" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="email" value="" maxlength="30">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- position id -->
                                    <td>Jabatan: </td>
                                    <td class="ps-3">
                                        <select required name="position_id" id="position_id" class="form-select">
                                            <option value="0" selected disabled></option>
                                            @foreach($positionsInput as $position)
                                                <option value="{{$position->id}}">{{$position->position_name}} - {{$position->department_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <!-- entry date  -->
                                    <td>Tanggal masuk: </td>
                                    <td class="ps-3"><input name="entry_date" id="entry_date" class="form-control" required type="date" ></td>
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

        <!-- Update Employee Modal -->
        <div class="modal fade" id="updateEmployeeModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="updateModalHeading"></h4>
                        <button type="button" class="btn-close update" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="employeeUpdateForm" name="employeeUpdateForm" class="form-horizontal">
                            <input type="hidden" name="employee_id" id="employee_id">
                            <table>
                                <tr>
                                    <!-- nik -->
                                    <td>NIK: </td>
                                    <td class="ps-3">
                                        <input name="nik_update" id="nik_update" disabled oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="" maxlength="8">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- name -->
                                    <td>Nama: </td>
                                    <td class="ps-3">
                                        <input name="name_update" id="name_update" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="" maxlength="50">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- email -->
                                    <td>Email: </td>
                                    <td class="ps-3">
                                        <input name="email_update" id="email_update" required oninput="this.value = this.value.toUpperCase()" class="form-control" type="email" value="" maxlength="30">
                                    </td>
                                </tr>
                                <tr>
                                    <!-- position id -->
                                    <td>Jabatan: </td>
                                    <td class="ps-3">
                                        <select required name="position_id_update" id="position_id_update" class="form-select">
                                            <option value="0" selected disabled></option>
                                            @foreach($positionsInput as $position)
                                                <option value="{{$position->id}}">{{$position->position_name}} - {{$position->department_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <!-- entry date  -->
                                    <td>Tanggal masuk: </td>
                                    <td class="ps-3"><input required="" name="entry_date_update" id="entry_date_update" class="form-control" type="date" value=""></td>
                                </tr>
                                <tr>
                                    <!-- out date  -->
                                    <td>Tanggal keluar: </td>
                                    <td class="ps-3"><input name="out_date_update" id="out_date_update" class="form-control" type="date" ></td>
                                </tr>
                            </table>
                             <!-- button submit -->
                             <br>
                             <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary" id="updateBtn" value="edit">Simpan Perubahan
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

            /*Add Loading When fire Ajax Request*/
            $(document).ajaxStart(function() {
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
            });
            
            /*Remove Loading When fire Ajax Request*/
            $(document).ajaxStop(function() {
                $('#loading').removeClass('loading');
                $('#loading-content').removeClass('loading-content');
            });

            var employeeTable = $('#employeeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employee.employee-table') }}",
                columns: [
                    {data: 'nik', name: 'nik'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'position_name', name: 'position_name'},
                    {data: 'department_name', name: 'department_name'},
                    {data: 'active', name: 'active'},
                    {data: 'entry_date', name: 'entry_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )},
                    {data: 'out_date', name: 'out_date', render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD-MM-YYYY','id' )},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });

            $('#createNewEmployee').click(function () {
                $('#insertBtn').val("create-employee");
                $('#employeeInsertForm').trigger("reset");
                $('#insertModalHeading').html("Input Karyawan Baru");
                $('#createEmployeeModal').modal('show');
            });

            $('#insertBtn').click(function (e) {
                e.preventDefault();
                $("#insertBtn").attr("disabled", true);
                $("#insertBtn").html('Menyimpan..');
                $.ajax({
                    data: $('#employeeInsertForm').serialize(),
                    url: "{{ route('employee.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#employeeInsertForm').trigger("reset");
                            $('#createEmployeeModal').modal('hide');
                        }
                        $('#insertBtn').html('Simpan');
                        $("#insertBtn").attr("disabled", false);
                        employeeTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#insertBtn').html('Simpan');
                        $("#insertBtn").attr("disabled", false);
                        alert('Error:'+data.message);

                    }
                });
            });

            $('body').on('click', '.editEmployee', function () {
                var employee_id = $(this).data('id');
                $.get("{{ url('employee') }}" +'/' + employee_id +'/edit', 
                    function (data) {
                        var date = new Date(data.entry_date);
                        var entry_date_formatted = date.getFullYear()+"-"+date.getMonth()+"-"+date.getDate();
                        $('#updateModalHeading').html("Edit Data Karyawan");
                        $('#updateBtn').val("edit-employee");
                        $('#updateEmployeeModal').modal('show');

                        $('#employee_id').val(data.id);
                        $('#nik_update').val(data.nik);
                        $('#name_update').val(data.name);
                        $('#email_update').val(data.email);
                        $('#position_id_update').val(data.position_id);
                        $('#entry_date_update').val(entry_date_formatted).change();
                        $('#out_date_update').val(data.out_date);
                    }
                )
            });

            $('#updateBtn').click(function (e) {
                var employee_id = $(this).data("id");
                e.preventDefault();
                $(this).html('Menyimpan..');
                $.ajax({
                    data: $('#employeeUpdateForm').serialize(),
                    url: "{{ url('employee') }}" +'/' + employee_id,
                    type: "PUT",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status=="Berhasil"){
                            $('#employeeUpdateForm').trigger("reset");
                            $('#updateEmployeeModal').modal('hide');
                        }
                        employeeTable.draw();
                        alert('Message: '+data.message);
                    },
                    error: function (data) {
                        $('#updateBtn').html('Simpan Perubahan');
                        alert('Error:'+data.message);
                    }
                });
            });

            $('body').on('click', '.deleteEmployee', function () {
                var employee_id = $(this).data("id");
                var confirmation = confirm("Apa Anda yakin ingin menghapus data karyawan ini?");

                if(confirmation){
                    $.ajax({
                        url: "{{ url('employee') }}"+'/'+employee_id,
                        method: "DELETE",
                        success: function (data) {
                            employeeTable.draw();
                            alert('Message: '+data.message);
                        },
                        error: function (data) {
                            alert('Error:'+data.message);
                        }
                    });
                }
            });

            $('.btn-close.insert').click(function () {
                $('#employeeInsertForm').trigger("reset");
                $('#createEmployeeModal').modal('hide');
            });

            $('.btn-close.update').click(function () {
                $('#employeeUpdateForm').trigger("reset");
                $('#updateEmployeeModal').modal('hide');
            });

            $('body').on('click', '.profileEmployee', function () {
                // var nik = $(this).data("nik");
                // var nik = $(this).val("nik");
                var nik = $(this).closest('tr').find(":first").text();
                window.location.href = "/employee-profile/"+ nik;
            });

        });
    </script>


</html>