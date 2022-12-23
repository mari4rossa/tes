<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//model
use App\Models\HR\Employee;
use App\Models\HR\Position;
use App\Models\HR\Department;
use App\Models\HR\Mutation;
use App\Models\HR\Import\ImportEmployee;
use App\Models\HR\Export\ExportEmployee;

//lib
use DB;
use Carbon\Carbon;
use DataTables;
use Excel;

class employeeController extends Controller
{
    //import
    public function importEmployeeFromExcel(Request $request){
        $this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		$file = $request->file('file');
        $fileName = rand().$file->getClientOriginalName();
        $file->move('file_employee',$fileName);

		Excel::import(new ImportEmployee, public_path('/file_employee/'.$fileName));
        return redirect()->back();
    }

    //export
    public function exportEmployeeToExcel(){
        $dateTime=Carbon::now()->format('d-m-Y H.i');
        $fileName="Daftar Karyawan Aktif per  ".$dateTime.".xlsx";
        return Excel::download(new ExportEmployee, $fileName);
    }

    //datatable
    public function employeeTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = Employee::join('departments','department_id','=','departments.id')
                                ->join('positions','position_id','=','positions.id')
                                ->select('employees.id','nik','name','email','positions.position_name','departments.department_name',
                                            DB::raw('(CASE WHEN employees.active = 1 THEN "Karyawan Aktif" ELSE "Mantan Karyawan" END) AS active'),
                                            'entry_date','out_date');
        }
        // dd($datas);
        return Datatables::of($datas)
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Profile" class="btn btn-info btn-sm profileEmployee">Profil</a>';
                        if($row->active === 'Karyawan Aktif'){
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editEmployee">Edit</a>';
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteEmployee">Hapus</a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positionsInput = Position::join('departments','department_id','=','departments.id')->select('positions.id','position_name','positions.department_id', 'department_name')->get();
        return view('hr.employee', ['positionsInput'=>$positionsInput ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status=null;
        $message=null;

        // $validation = $request->validate([
        //     'email' => 'required|email:rfc,dns'
        // ]);
        // if(!$validation){
        //     $message='email kosong';
        // }
          
        $nik = $request->nik;
        $name = $request->name;
        $email = $request->email;
        $position_id = $request->position_id;
        $department= Position::where('id','=',$position_id)->select('department_id')->first();
        $department_id=$department->department_id;
        $entry_date = $request->entry_date;

        if($nik!='' && $name!='' && $email!='' && $entry_date!=null){
            if (str_contains(strtoupper($email), '@POLYTRON.COM')){
                DB::beginTransaction();
                //cek nik nya dah ada apa blm
                $checkEmployee = Employee::where('nik','=',$nik)->select('nik', 'name')->first();
                if($checkEmployee==null){
                    $insertEmployee = new Employee([
                        'nik'=>$nik,
                        'name'=>$name,
                        'email'=>$email,
                        'position_id'=>$position_id,
                        'department_id'=>$department_id,
                        'active'=>1,
                        'entry_date'=>$entry_date,
                        'created_at'=>Carbon::now()
                    ]);
                    $insertEmployee->save();
                    if($insertEmployee){
                        DB::commit();
                        $status='Berhasil'; $message='Anda berhasil menambah karyawan baru.';
                    } else{
                        DB::rollback();
                        $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                    }
                } else{
                    $status='Gagal'; $message='NIK yang Anda masukkan sudah terdaftar untuk karyawan dengan nama: '.$checkEmployee->name.'.';
                }
            } else {
                $status='Gagal'; $message='Email karyawan wajib menggunakan domain Polytron.';
            }
        } elseif($nik==''){
            $status='Gagal'; $message='NIK tidak boleh hanya berisi spasi.';
        }elseif($name==''){
            $status='Gagal'; $message='Nama karyawan tidak boleh hanya berisi spasi.';
        }elseif($email==''){
            $status='Gagal'; $message='Email karyawan tidak boleh hanya berisi spasi.';
        }elseif($entry_date==null){
            $status='Gagal'; $message='Tanggal masuk harus terisi.';
        }

        return response()->json(['status'=>$status, 'message'=>$message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //tanggal keluar harus kurang dari sama dengan tanggal sistem 
        //kalau tanggal keluarnya diisi, maka otomatis active jadi 0
        //kalau active 0 ga bisa di edit, maka diawal code harus cek active dulu

        $status=null;
        $message=null;

        $id=$request->employee_id;
        $name = trim($request->name_update);
        $email = trim($request->email_update);
        $position_id = $request->position_id_update;
        $department= Position::where('id','=',$position_id)->select('department_id')->first();
        $department_id=$department->department_id;

        if($request->entry_date_update != null){
            $entry_date = $request->entry_date_update;
        } elseif($request->entry_date_update == null) {
            $entry_date = '';
        }
        if($request->out_date_update){
            $out_date = $request->out_date_update;
        } elseif($request->out_date_update == null) {
            $out_date = '';
        }
        
        $now_date = Carbon::now()->format('Y-m-d');

        if($name!='' && $email!='' && $entry_date != ''){
            //cek activenya, kalau sudah keluar tdk boleh diedit
            DB::beginTransaction();
            $updateEmployee = Employee::find($id);

            if($updateEmployee->active==1){
                $mutationEmployeeId = $updateEmployee->id;
                $position_id_mutation = $updateEmployee->position_id;
                $old_position = Position::join('departments', 'department_id', '=', 'departments.id')
                                            ->where('positions.id','=', $updateEmployee->position_id)->first();
                $old_position_name = $old_position->position_name;               
                $old_department_name = $old_position->department_name;

                // dd($mutationEmployeeId." ".$position_id_mutation." ".$old_position_name." ".$old_department_name);

                $updateEmployee->name = $name;
                $updateEmployee->email = $email;
                $updateEmployee->position_id = $position_id;
                $updateEmployee->department_id = $department_id;
                $updateEmployee->entry_date = $entry_date;
                $updateEmployee->updated_at = Carbon::now();


                if($out_date!=''){
                    if(strtotime($out_date)<=strtotime($now_date)){
                        if(strtotime($out_date)>=strtotime($entry_date)){

                            $updateEmployee->out_date = $out_date;
                            $updateEmployee->active = 0;
                            $updateEmployee->save();

                            if($updateEmployee){
                                DB::commit();
                                $status='Berhasil'; $message='Anda berhasil membaharui data karyawan.';
                            } else{
                                DB::rollback();
                                $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                            }

                        } elseif(strtotime($out_date)<strtotime($entry_date)){
                            DB::rollback();
                            $status='Gagal'; $message='Tanggal keluar tidak boleh mendahului tanggal masuk.';
                        }
                    } elseif(strtotime($out_date)>strtotime($now_date)){
                        DB::rollback();
                        $status='Gagal'; $message='Tanggal keluar karyawan di masa depan tidak boleh diinput sebelum waktunya.';
                    } 
                } elseif($out_date==''){
                    $updateEmployee->save();
                    if($updateEmployee){
                        //cek ada perubahan posisi ga?
                        if($position_id_mutation != $position_id){
                            //kalau ada insert ke riwayat mutasi
                            $mutationEmployee = Employee::find($mutationEmployeeId);
                            $new_position = Position::join('departments', 'department_id', '=', 'departments.id')
                                                        ->where('positions.id','=', $mutationEmployee->position_id)->first();
                            $new_position_name = $new_position->position_name;               
                            $new_department_name = $new_position->department_name;

                            $insertMutation = new Mutation([
                                'employee_id'=>$mutationEmployee->id,
                                'nik'=>$mutationEmployee->nik,
                                'name'=>$mutationEmployee->name,
                                'email'=>$mutationEmployee->email,
                                'old_position'=>$old_position_name,
                                'new_position'=> $new_position_name,
                                'old_department'=>$old_department_name,
                                'new_department'=>$new_department_name,
                                'start_date'=>Carbon::now()->format('Y-m-d'),
                                'created_at'=>Carbon::now(),
                            ]);
                            $insertMutation->save();
                            if($insertMutation){
                                DB::commit();
                                $status='Berhasil'; $message='Anda berhasil membaharui data karyawan dan menambah data mutasi.';
                            } else {
                                DB::rollback();
                                $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                            }
                        } else {
                            DB::commit();
                            $status='Berhasil'; $message='Anda berhasil membaharui data karyawan.';
                        }  
                    } else {
                        DB::rollback();
                        $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                    }
                }
            } elseif($updateEmployee->active==0){
                $status='Gagal'; $message='Data mantan karyawan tidak boleh diedit.';
            }
        } elseif($name==''){
            $status='Gagal'; $message='Nama karyawan tidak boleh hanya berisi spasi.';
        } elseif($email==''){
            $status='Gagal'; $message='Email karyawan tidak boleh hanya berisi spasi.';
        } elseif($entry_date == ''){
            $status='Gagal'; $message='Tanggal masuk harus terisi.';
        }
        return response()->json(['status'=>$status, 'message'=>$message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // yg boleh dihapus hanya yg active
        $status=null;
        $message=null;

        DB::beginTransaction();
        $employee = Employee::find($id);
        if($employee->active==1){
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $employee->delete();
            if($employee){
                DB::commit();
                $status="Berhasil"; $message="Anda berhasil menghapus data karyawan.";
            } else{
                DB::rollback();
                $status="Gagal"; $message="Mohon menghubungi admin, gagal menonaktifkan data jabatan.";
            }
        } elseif($employee->active==0){
            $status="Gagal"; $message="Data mantan karyawan tidak boleh dihapus.";
        }
        return response()->json(['status'=>$status, 'message'=>$message]);
    }
}
