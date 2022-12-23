<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//model
use App\Models\HR\Position;
use App\Models\HR\Department;
use App\Models\HR\Export\ExportPosition;
use App\Models\HR\Import\ImportPosition;

//lib
use DB;
use Carbon\Carbon;
use DataTables;
use Excel;

class positionController extends Controller
{
    //import
    public function importPositionFromExcel(Request $request){
        $this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		$file = $request->file('file');
        $fileName = rand().$file->getClientOriginalName();
        $file->move('file_position',$fileName);

		Excel::import(new ImportPosition, public_path('/file_position/'.$fileName));
        return redirect()->back();
    }

    //export
    public function exportPositionToExcel(){
        $dateTime=Carbon::now()->format('d-m-Y H.i');
        $fileName="Daftar Jabatan per  ".$dateTime.".xlsx";
        return Excel::download(new ExportPosition, $fileName);
    }

    //datatable
    public function positionTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = Position::join('departments', 'department_id', '=', 'departments.id')->where('positions.active','=', 1)->select('positions.id','position_name','department_code','department_name');
        }
        return Datatables::of($datas)
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editPosition">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletePosition">Non-aktifkan</a>';
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
        $departments = Department::where('active','=',1)->get();
        //nanti dibalikin sama view, buat ngisi department pas insert position
        return view('hr.position', ['departments'=>$departments]);
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

        $position_name = trim($request->position_name);
        $department_id = $request->department_id;

        if($position_name != '' && $department_id != ''){
            DB::beginTransaction();
            //cek nama posisi sudah ada apa blm di departmen yang dipilih
            $checkPosition = Position::where('position_name','=',$position_name)->where('department_id','=',$department_id)->first();
            if($checkPosition==null){
                $insertPosition = new Position([
                    'position_name'=>$position_name,
                    'department_id'=>$department_id,
                    'active'=>1,
                    'created_at'=>Carbon::now()
                ]);
                $insertPosition->save();
                if($insertPosition){
                    DB::commit();
                    $status='Berhasil'; $message='Anda berhasil menambah data jabatan baru.';
                } else{
                    DB::rollback();
                    $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                }
            } elseif($checkPosition->position_name!=null){
                if($checkPosition->active == 0){
                    $checkPosition->active = 1;
                    $checkPosition->position_name = $position_name;
                    $checkPosition->department_id = $department_id;
                    $checkPosition->updated_at = Carbon::now();
                    $checkPosition->save();
                    if($checkPosition){
                        DB::commit();
                        $status='Berhasil'; $message='Anda berhasil menambah data jabatan baru.';
                    } else{
                        Db::rollback();
                        $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                    }
                } elseif($checkDepartment->active == 1){
                    $status='Gagal'; $message='Jabatan dengan nama '.$position_name.' di department yang Anda pilih, sudah ada.';
                }
            }
        }elseif($position_name == ''){
            $status='Gagal'; $message='Nama jabatan tidak boleh hanya berisi spasi.';
        }elseif($department_id == ''){
            $status='Gagal'; $message='Anda harus memilih department mana jabatan ini berada.';
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
        $position = Position::find($id);
        return response()->json($position);
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

        $id = $request->position_id;
        $position_name = trim($request->position_name_update);
        $department_id = $request->department_id_update;

        if($position_name != '' && $department_id != ''){
            DB::beginTransaction();
            //posisi di department itu dah ada apa blm?
            $checkPosition = Position::where('position_name','=',$position_name)->where('department_id','=',$department_id)->first();
            $thisPosition = Position::find($id);

            if($checkPosition==null){
                //update position
                $thisPosition->position_name=$position_name;
                $thisPosition->department_id=$department_id;
                $thisPosition->active=1;
                $thisPosition->updated_at=Carbon::now();
                $thisPosition->save();
                if($thisPosition){
                    DB::commit();
                    $status='Berhasil'; $message='Anda berhasil membaharui data jabatan.';
                } else{
                    DB::rollback();
                    $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                }
            } elseif($checkPosition!=null){
                //cek activenya
                if($checkPosition->active==0){
                    //aktifkan checkDepartment
                    $checkPosition->active=1;
                    $checkPosition->updated_at=Carbon::now();
                    $checkPosition->save();
                    if($checkPosition){
                        //matikan yg lagi diupdate
                        $thisPosition->active=0;
                        $thisPosition->updated_at=Carbon::now();
                        $thisPosition->save();
                        if($thisPosition){
                            DB::commit();
                            $status='Berhasil'; $message='Anda berhasil membaharui data jabatan.';
                        } else{
                            DB::rollback();
                            $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                        }
                    } else{
                        DB::rollback();
                        $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                    }

                } elseif($checkPosition->active==1){
                    $status='Gagal'; $message='Jabatan yang Anda masukkan dengan department-nya sudah ada dan aktif.';
                }
            }
        }elseif($position_name == ''){
            $status='Gagal'; $message='Nama jabatan tidak boleh hanya berisi spasi.';
        }elseif($department_id == ''){
            $status='Gagal'; $message='Anda harus memilih department mana jabatan ini berada.';
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
        $position = Position::find($id);
        $position->active=0;
        $position->updated_at=Carbon::now();
        $position->save();

        if($position){
            DB::commit();
            $status="Berhasil"; $message="Anda berhasil menonaktifkan jabatan.";
        } else{
            DB::rollback();
            $status="Gagal"; $message="Mohon menghubungi admin, gagal menonaktifkan data jabatan.";
        }
        return response()->json(['status'=>$status, 'message'=>$message]);
    }
}
