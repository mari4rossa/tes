<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//lib
use DataTables;
use DB;

//model
use App\Models\HR\Employee;
use App\Models\HR\Position;
use App\Models\HR\Department;

use App\Models\HR\Mutation;
use App\Models\Training\TrainingHistory;

class employeeProfileController extends Controller
{
    
    //datatable employee
    public function employeeTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = Employee::join('departments','department_id','=','departments.id')
                                ->join('positions','position_id','=','positions.id')
                                ->select('employees.id','nik','name','email','positions.position_name','departments.department_name',
                                            DB::raw('(CASE WHEN employees.active = 1 THEN "Karyawan Aktif" ELSE "Mantan Karyawan" END) AS active'),
                                            'entry_date','out_date')->where('nik','=',$request->nik);
        }
        // dd($datas);
        return Datatables::of($datas)->make(true);
    }
    //datatable mutastion
    public function mutationTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = Mutation::where('nik','=',$request->nik)->get();
        }
        return Datatables::of($datas)->make(true);
    }
    //datatable training history
    public function trainingHistoryTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = TrainingHistory::where('nik','=',$request->nik)->get();
        }
        return Datatables::of($datas)->make(true);
    }

    //viewnya
    public function tampilan($nik){
        return view('hr.employee_profile',['nik'=>$nik]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        //
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
        //
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
