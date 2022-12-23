<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//model
use App\Models\HR\Department;
use App\Models\HR\Export\ExportDepartment;
use App\Models\HR\Import\ImportDepartment;

//lib
use DB;
use Carbon\Carbon;
use DataTables;
use Excel;

class departmentController extends Controller
{
    //import
    public function importDepartmentFromExcel(Request $request){
        $this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		$file = $request->file('file');
        $fileName = rand().$file->getClientOriginalName();
        $file->move('file_department',$fileName);

		Excel::import(new ImportDepartment, public_path('/file_department/'.$fileName));
        return redirect()->back();
    }

    //export
    public function exportDepartmentToExcel(){
        $dateTime=Carbon::now()->format('d-m-Y H.i');
        $fileName="Data Divisi ".$dateTime.".xlsx";
        return Excel::download(new ExportDepartment, $fileName);
    }

    //datatable
    public function departmentTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = Department::where('active', 1)->get();
        }
        return Datatables::of($datas)
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editDepartment">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteDepartment">Non-aktifkan</a>';
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
        return view('hr.department');
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

        $department_code = trim($request->department_code);
        $department_name = trim($request->department_name);

        if($department_code != '' && $department_name != ''){
            DB::beginTransaction();
            //cek kodenya sudah ada apa belum, karena unique
            $checkDepartment = Department::where('department_code','=',$department_code)->first();
            if($checkDepartment==null){
                $insertDepartment = new Department([
                    'department_code'=>$department_code,
                    'department_name'=>$department_name,
                    'active'=>1,
                    'created_at'=>Carbon::now()
                ]);
                $insertDepartment->save();
                if($insertDepartment){
                    DB::commit();
                    $status='Berhasil'; $message='Anda berhasil menambah data divisi baru.';
                } else{
                    DB::rollback();
                    $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                }
            } elseif($checkDepartment->department_code!=null){
                //cek aktifnya
                if($checkDepartment->active == 0){
                    $checkDepartment->active = 1;
                    $checkDepartment->department_name = $department_name;
                    $checkDepartment->save();
                    if($checkDepartment){
                        DB::commit();
                        $status='Berhasil'; $message='Anda berhasil menambah data divisi baru.';
                    } else{
                        Db::rollback();
                        $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                    }
                } elseif($checkDepartment->active == 1){
                    $status='Gagal'; $message='Divisi dengan kode '.$department_code.' sudah ada, dengan nama '.$checkDepartment->department_name.'.';
                }
            }

        }elseif($department_code == ''){
            $status='Gagal'; $message='Kode divisi tidak boleh hanya berisi spasi.';
        }elseif($department_name == ''){
            $status='Gagal'; $message='Nama divisi tidak boleh hanya berisi spasi.';
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
        $department = Department::find($id);
        return response()->json($department);
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
        $status=null;
        $message=null;

        $department_code = trim($request->department_code_update);
        $department_name = trim($request->department_name_update);

        if($department_code != '' && $department_name != ''){
            DB::beginTransaction();
            //cek kodenya sudah ada apa belum, karena unique
            $checkDepartment = Department::where('department_code','=',$department_code)->first();
            $thisDepartment = Department::find($id);
            if($checkDepartment==null){
                //update department
                $thisDepartment->department_code=$department_code;
                $thisDepartment->department_name=$department_name;
                $thisDepartment->active=1;
                $thisDepartment->updated_at=Carbon::now();
                $thisDepartment->save();
                if($thisDepartment){
                    DB::commit();
                    $status='Berhasil'; $message='Anda berhasil membaharui data divisi.';
                } else{
                    DB::rollback();
                    $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                }
            } elseif($checkDepartment->department_code!=null){
                //cek activenya
                if($checkDepartment->active==0){
                    //aktifkan checkDepartment
                    $checkDepartment->active=1;
                    $checkDepartment->department_name=$department_name;
                    $checkDepartment->updated_at=Carbon::now();
                    $checkDepartment->save();
                    if($checkDepartment){
                        //matikan yg lagi diupdate
                        $thisDepartment->active=0;
                        $thisDepartment->updated_at=Carbon::now();
                        $thisDepartment->save();
                        if($thisDepartment){
                            DB::commit();
                            $status='Berhasil'; $message='Anda berhasil membaharui data divisi.';
                        } else{
                            DB::rollback();
                            $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                        }
                    } else{
                        DB::rollback();
                        $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                    }

                } elseif($checkDepartment->active==1){
                    //cek department namenya
                    if($checkDepartment->department_name==$department_name){
                        $status='Gagal'; $message='Divisi dengan kode '.$department_code.' sudah ada, dengan nama '.$checkDepartment->department_name.'.';
                    } else {
                        $checkDepartment->department_name=$department_name;
                        $checkDepartment->updated_at=Carbon::now();
                        $checkDepartment->save();
                        if($checkDepartment){
                            DB::commit();
                            $status='Berhasil'; $message='Anda berhasil membaharui data divisi.';
                        } else{
                            DB::rollback();
                            $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                        }
                    }
                    
                }
            }

        }elseif($department_code == ''){
            $status='Gagal'; $message='Kode divisi tidak boleh hanya berisi spasi.';
        }elseif($department_name == ''){
            $status='Gagal'; $message='Nama divisi tidak boleh hanya berisi spasi.';
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
        $status=null;
        $message=null;

        DB::beginTransaction();
        $department = Department::find($id);
        $department->active=0;
        $department->updated_at=Carbon::now();
        $department->save();

        if($department){
            DB::commit();
            $status="Berhasil"; $message="Anda berhasil menonaktifkan divisi.";
        } else{
            DB::rollback();
            $status="Gagal"; $message="Mohon menghubungi admin, gagal menonaktifkan data department.";
        }
        return response()->json(['status'=>$status, 'message'=>$message]);
    }
}
