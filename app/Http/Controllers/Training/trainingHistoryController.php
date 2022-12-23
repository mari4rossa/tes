<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//model
use App\Models\HR\Employee;
use App\Models\HR\Position;
use App\Models\HR\Department;
use App\Models\Training\Export\ExportTrainingHistory;

use App\Models\Training\Training;
use App\Models\Training\Trainer;
use App\Models\Training\TrainingHistory;

//lib
use DB;
use Carbon\Carbon;
use DataTables;
use Excel;

class trainingHistoryController extends Controller
{
    //export
    public function exportTrainingHistoryToExcel(){
        $dateTime=Carbon::now()->format('d-m-Y H.i');
        $fileName="Data Pelatihan Karyawan ".$dateTime.".xlsx";
        return Excel::download(new ExportTrainingHistory, $fileName);
    }
    //datatable
    public function trainingHistoryTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = TrainingHistory::query();
        }
        // dd($datas);
        return Datatables::of($datas)
                    ->addColumn('action', function($row){
                        if($row->end_date === null){
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editTrainingHistory">Input Tanggal Selesai</a>';
                            return $btn;
                        }
                        
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
        $employees=Employee::where('active','=',1)->get();
        $trainings=Training::where('active','=',1)->get();
        return view('training.training_history', [
            'employees'=>$employees,
            'trainings'=>$trainings
        ]);
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

        $employee_id = $request->employee_id;
        $training_id = $request->training_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if($employee_id!=''  && $training_id!='' && $start_date!=null){
            //cek karyawan sdh ambil trainingnya apa belum??
            DB::beginTransaction();
            $checkTrainingHistory = TrainingHistory::where('employee_id','=',$employee_id)
                                                        ->where('training_id','=', $training_id)
                                                        ->first();
            if($checkTrainingHistory==null){
                //ambil data employee - nik, name, email, position id, department id
                $employee = Employee::where('id','=',$employee_id)->first();
                $nik = $employee->nik;
                $employee_name = $employee->name;
                $employee_email = $employee->email;
                $position_id = $employee->position_id;
                $department_id = $employee->department_id;
                // ambil data position - position name
                $position = Position::where('id','=',$position_id)->select('position_name')->first();
                $position_name = $position->position_name;
                //ambil data department - department name
                $department = Department::where('id','=',$department_id)->select('department_name')->first();
                $department_name = $department->department_name;
                //ambil data training - training_name, trainer_id
                $training = Training::where('id','=',$training_id)->first();
                $training_name = $training->training_name;
                $trainer_id = $training->trainer_id;
                //ambil data trainer - trainer_code, name, email
                $trainer = Trainer::where('id','=',$trainer_id)->select('trainer_code','name','email')->first();
                $trainer_code = $trainer->trainer_code;
                $trainer_name = $trainer->name;
                $trainer_email = $trainer->email;

                $insertTrainingHistory = new TrainingHistory([
                    'employee_id'=>$employee_id,
                    'nik'=>$nik,
                    'name'=>$employee_name,
                    'email'=>$employee_email,
                    'position_id'=>$position_id,
                    'position_name'=>$position_name,
                    'department_id'=>$department_id,
                    'department_name'=>$department_name,
                    'training_id'=>$training_id,
                    'training_name'=>$training_name,
                    'trainer_id'=>$trainer_id,
                    'trainer_code'=>$trainer_code,
                    'trainer_name'=>$trainer_name,
                    'trainer_email'=>$trainer_email,
                    'start_date'=>$start_date,
                    'end_date'=>$end_date,
                    'created_at'=>Carbon::now()
                ]);
                $insertTrainingHistory->save();
                if($insertTrainingHistory){
                    DB::commit();
                    $status="Berhasil"; $message="Anda berhasil menambah data pelatihan karyawan.";
                } else{
                    DB::rollback();
                    $status="Gagal"; $message="Mohon hubungi admin, gagal input data ke database.";
                }
            } elseif ($checkTrainingHistory!=null) {
                $start_date = Carbon::parse($checkTrainingHistory->start_date)->format('d-m-Y');
                $status="Gagal"; $message="Karyawan telah mengikuti pelatihan tersebut pada tanggal ".$start_date.".";
            }
        } elseif($employee_id==''){
            $status="Gagal"; $message="Pilihan karyawan tidak boleh kosong.";
        } elseif($training_id==''){
            $status="Gagal"; $message="Pilihan pelatihan tidak boleh kosong.";
        } elseif($start_date==null){
            $status="Gagal"; $message="Tanggal mulai tidak boleh kosong.";
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
        $training_history = TrainingHistory::where('id','=',$id)->select('id','end_date')->first();
        return response()->json($training_history);
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

        $id = $request->training_history_id;
        $end_date = $request->end_date_update;

        // dd($id."  ".$end_date);

        //cek sdh komplit datanya atau blm?
        DB::beginTransaction();
        $checkTrainingHistory = TrainingHistory::find($id);
        $start_date = $checkTrainingHistory->start_date;

        if($checkTrainingHistory->end_date==null){
            if($end_date!=null){
                if(strtotime($end_date)>=strtotime($start_date)){
                    $checkTrainingHistory->end_date=$end_date;
                    $checkTrainingHistory->save();
                    if($checkTrainingHistory){
                        DB::commit();
                        $status="Berhasil"; $message="Anda berhasil menambahkan tanggal selesai.";
                    } else{
                        DB::rollback();
                        $status="Gagal"; $message="Mohon hubungi admin, gagal input data ke database.";
                    }
                } elseif(strtotime($end_date)<strtotime($start_date)){
                    $status="Gagal"; $message="Tanggal selesai tidak boleh medahului tanggal mulai.";
                }
            } elseif($end_date==null){
                $status="Gagal"; $message="Gagal memasukkan tanggal selesai karena Anda kosongkan.";
            }
        } elseif($checkTrainingHistory->end_date!=null){
            $status="Gagal"; $message="Anda tidak dapat mengubah riwayat pelatihan karyawan.";
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
        //
    }
}
