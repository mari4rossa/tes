<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//model
use App\Models\Training\Training;
use App\Models\Training\Trainer;
use App\Models\Training\Export\ExportTraining;
use App\Models\Training\Import\ImportTraining;

//lib
use DB;
use Carbon\Carbon;
use DataTables;
use Excel;

class trainingController extends Controller
{
    //import
    public function importTrainingFromExcel(Request $request){
        $this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		$file = $request->file('file');
        $fileName = rand().$file->getClientOriginalName();
        $file->move('file_training',$fileName);

		Excel::import(new ImportTraining, public_path('/file_training/'.$fileName));
        return redirect()->back();
    }

    //export
    public function exportTrainingToExcel(){
        $dateTime=Carbon::now()->format('d-m-Y H.i');
        $fileName="Data Program Pelatihan ".$dateTime.".xlsx";
        return Excel::download(new ExportTraining, $fileName);
    }

    //datatable
    public function trainingTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = Training::join('trainers', 'trainer_id', '=', 'trainers.id')
                        ->where('trainings.active','=', 1)
                        ->select('trainings.id','training_name','trainer_id','trainer_code','trainers.name');
        }
        return Datatables::of($datas)
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editTraining">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteTraining">Non-aktifkan</a>';
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
        $trainers = Trainer::where('active','=',1)->get();
        return view('training.training', ['trainers'=>$trainers]);
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

        $training_name = trim($request->training_name);
        $trainer_id = $request->trainer_id;
        if($training_name!='' && $trainer_id!=''){
            DB::beginTransaction();
            //cari nama trainingnya dah ada apa belum?
            $checkTraining = Training::where('training_name','=',$training_name)->first();
            if($checkTraining!=null){
                //cek activenya
                if($checkTraining->active==0){
                    //aktifkan
                    $checkTraining->active=1;
                    $checkTraining->trainer_id=$trainer_id;
                    $checkTraining->updated_at=Carbon::now();
                    $checkTraining->save();
                    if($checkTraining){
                        DB::commit();
                        $status="Berhasil"; $message="Anda berhasil menambah data pelatihan.";
                    } else{
                        DB::rollback();
                        $status="Gagal"; $message="Mohon hubungi admin, gagal input data ke database.";
                    }
                } elseif($checkTraining->active==1){
                    $status="Gagal"; $message="Program pelatihan sudah ada.";
                }
            } elseif($checkTraining==null){
                //insert baru
                $insertTraining = new Training([
                    'training_name'=>$training_name,
                    'trainer_id'=>$trainer_id,
                    'active'=>1,
                    'created_at'=>Carbon::now(),
                ]);
                $insertTraining->save();
                if($insertTraining){
                    DB::commit();
                    $status="Berhasil"; $message="Anda berhasil menambah data pelatihan.";
                } else{
                    DB::rollback();
                    $status="Gagal"; $message="Mohon hubungi admin, gagal input data ke database.";
                }
            }
        } elseif($training_name==''){
            $status="Gagal"; $message="Nama program pelatihan tidak boleh hanya berisi spasi.";
        } elseif($trainer_id==''){
            $status="Gagal"; $message="Pilihan pelatih tidak boleh dikosongi.";
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
        $training=Training::find($id);
        return response()->json($training);
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

        $id=$request->training_id;
        $training_name = trim($request->training_name_update);
        $trainer_id = $request->trainer_id_update;

        if($training_name!='' && $trainer_id!=''){
            DB::beginTransaction();
            //cek trainingnya dah ada apa blm
            $checkTraining = Training::where('training_name','=',$training_name)->where('trainer_id','=',$trainer_id)->first();
            if($checkTraining!=null){
                //cek activenya
                if($checkTraining->active==0){
                    //aktifkan
                    $checkTraining->active=1;
                    $checkTraining->trainer_id=$trainer_id;
                    $checkTraining->updated_at=Carbon::now();
                    $checkTraining->save();
                    if($checkTraining){
                        DB::commit();
                        $status="Berhasil"; $message="Anda berhasil membaharui data pelatihan.";
                    } else{
                        DB::rollback();
                        $status="Gagal"; $message="Mohon hubungi admin, gagal input data ke database.";
                    }
                } elseif($checkTraining->active==1){
                    $status="Gagal"; $message="Nama program pelatihan sudah ada.";
                }
            } elseif($checkTraining==null){
                //update aja
                $thisTraining = Training::find($id);
                $thisTraining->training_name=$training_name;
                $thisTraining->trainer_id=$trainer_id;
                $thisTraining->active=1;
                $thisTraining->updated_at=Carbon::now();
                $thisTraining->save();
                if($thisTraining){
                    DB::commit();
                    $status="Berhasil"; $message="Anda berhasil membaharui data pelatihan.";
                } else{
                    DB::rollback();
                    $status="Gagal"; $message="Mohon hubungi admin, gagal input data ke database.";
                }
            }
        } elseif($training_name==''){
            $status="Gagal"; $message="Nama program pelatihan tidak boleh hanya berisi spasi.";
        } elseif($trainer_id==''){
            $status="Gagal"; $message="Pilihan pelatih tidak boleh dikosongi.";
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
        $training = Training::find($id);
        $training->active=0;
        $training->updated_at=Carbon::now();
        $training->save();

        if($training){
            DB::commit();
            $status="Berhasil"; $message="Anda berhasil menonaktifkan program pelatihan.";
        } else{
            DB::rollback();
            $status="Gagal"; $message="Mohon menghubungi admin, gagal menonaktifkan data program pelatihan.";
        }
        return response()->json(['status'=>$status, 'message'=>$message]);
    }
}
