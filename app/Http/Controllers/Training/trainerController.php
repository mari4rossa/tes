<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//model
use App\Models\Training\Trainer;
use App\Models\Training\Export\ExportTrainer;
use App\Models\Training\Import\ImportTrainer;

//lib
use DB;
use Carbon\Carbon;
use DataTables;
use Excel;

class trainerController extends Controller
{
    //import
    public function importTrainerFromExcel(Request $request){
        $this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		$file = $request->file('file');
        $fileName = rand().$file->getClientOriginalName();
        $file->move('file_trainer',$fileName);

		Excel::import(new ImportTrainer, public_path('/file_trainer/'.$fileName));
        return redirect()->back();
    }

    //export
    public function exportTrainerToExcel(){
        $dateTime=Carbon::now()->format('d-m-Y H.i');
        $fileName="Data Pelatih ".$dateTime.".xlsx";
        return Excel::download(new ExportTrainer, $fileName);
    }

    //datatable
    public function trainerTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = Trainer::where('active', 1)->get();
        }
        return Datatables::of($datas)
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editTrainer">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteTrainer">Hapus</a>';
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
        return view('training.trainer');
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

        $trainer_code = trim($request->trainer_code);
        $trainer_name= trim($request->trainer_name);
        $trainer_email = trim($request->trainer_email);

        if($trainer_name != '' && $trainer_email != ''){
            DB::beginTransaction();
            //cek kodenya sudah ada apa belum, karena unique
            $checkTrainer = Trainer::where('trainer_code','=',$trainer_code)->first();

            if($checkTrainer==null){
                $insertTrainer = new Trainer([
                    'trainer_code' => $trainer_code,
                    'name' => $trainer_name,
                    'email' => $trainer_email,
                    'active' => 1,
                    'created_at'=>Carbon::now()
                ]);
                $insertTrainer->save();
                if($insertTrainer){
                    DB::commit();
                    $status='Berhasil'; $message='Anda berhasil menambah data pelatih baru.';
                } else{
                    DB::rollback();
                    $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                }
            } elseif($checkTrainer->trainer_code != null){
                //cek aktifnya
                if($checkTrainer->active == 0){
                    $checkTrainer->active = 1;
                    $checkTrainer->name = $trainer_name;
                    $checkTrainer->email = $trainer_email;
                    $checkTrainer->updated_at = Carbon::now();
                    $checkTrainer->save();
                    if($checkTrainer){
                        DB::commit();
                        $status='Berhasil'; $message='Anda berhasil menambah data pelatih baru.';
                    } else{
                        Db::rollback();
                        $status='Gagal'; $message='Mohon hubungi admin, gagal input data ke database.';
                    }
                } elseif($checkTrainer->active == 1){
                    $status='Gagal'; $message='Pelatih dengan kode '.$trainer_code.' sudah ada, dengan nama '.$checkTrainer->name.'.';
                }
            }

        }elseif($trainer_name == ''){
            $status='Gagal'; $message='Nama pelatih tidak boleh hanya berisi spasi.';
        }elseif($trainer_email == ''){
            $status='Gagal'; $message='Email pelatih tidak boleh hanya berisi spasi.';
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
        $trainer= Trainer::find($id);
        return response()->json($trainer);
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

        $trainer_code = trim($request->trainer_code_update);
        $trainer_name = trim($request->trainer_name_update);
        $trainer_email = trim($request->trainer_email_update);

        if($trainer_code!='' && $trainer_name!='' && $trainer_email!=''){
            DB::beginTransaction();
            //cek kodenya sudah ada apa belum, karena unique
            $checkTrainer = Trainer::where('trainer_code','=',$trainer_code)->first();
            $thisTrainer = Trainer::find($id);
            if($checkTrainer==null){
                //update department
                $thisTrainer->trainer_code=$trainer_code;
                $thisTrainer->name=$trainer_name;
                $thisTrainer->email=$trainer_email;
                $thisTrainer->active=1;
                $thisTrainer->updated_at=Carbon::now();
                $thisTrainer->save();
                if($thisTrainer){
                    DB::commit();
                    $status='Berhasil'; $message='Anda berhasil membaharui data pelatih.';
                } else{
                    DB::rollback();
                    $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                }
            } elseif($checkTrainer->trainer_code!=null){
                //cek activenya
                if($checkTrainer->active==0){
                    //aktifkan checkDepartment
                    $checkTrainer->active=1;
                    $checkTrainer->name=$trainer_name;
                    $checkTrainer->email=$trainer_email;
                    $checkTrainer->updated_at=Carbon::now();
                    $checkTrainer->save();
                    if($checkTrainer){
                        //matikan yg lagi diupdate
                        $thisTrainer->active=0;
                        $thisTrainer->updated_at=Carbon::now();
                        $thisTrainer->save();
                        if($thisTrainer){
                            DB::commit();
                            $status='Berhasil'; $message='Anda berhasil membaharui data pelatih.';
                        } else{
                            DB::rollback();
                            $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                        }
                    } else{
                        DB::rollback();
                        $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                    }

                } elseif($checkTrainer->active==1){
                    //cek trainer namenya
                    if($checkTrainer->name==$trainer_name){
                        $status='Gagal'; $message='Pelatih dengan kode '.$trainer_code.' sudah ada, dengan nama '.$checkTrainer->name.'.';
                    } else {
                        $checkTrainer->name=$trainer_name;
                        $checkTrainer->email=$trainer_email;
                        $checkTrainer->updated_at=Carbon::now();
                        $checkTrainer->save();
                        if($checkTrainer){
                            DB::commit();
                            $status='Berhasil'; $message='Anda berhasil membaharui data pelatih.';
                        } else{
                            DB::rollback();
                            $status='Gagal'; $message='Mohon hubungi admin, gagal update data ke database.';
                        }
                    }
                    
                }
            }

        }elseif($trainer_code == ''){
            $status='Gagal'; $message='Kode pelatih tidak boleh hanya berisi spasi.';
        }elseif($trainer_name == ''){
            $status='Gagal'; $message='Nama pelatih tidak boleh hanya berisi spasi.';
        }elseif($trainer_email == ''){
            $status='Gagal'; $message='Email pelatih tidak boleh hanya berisi spasi.';
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
        $trainer = Trainer::find($id);
        $trainer->active=0;
        $trainer->updated_at=Carbon::now();
        $trainer->save();

        if($trainer){
            DB::commit();
            $status="Berhasil"; $message="Anda berhasil menonaktifkan pelatih.";
        } else{
            DB::rollback();
            $status="Gagal"; $message="Mohon menghubungi admin, gagal menonaktifkan data pelatih.";
        }
        return response()->json(['status'=>$status, 'message'=>$message]);
    }
}
