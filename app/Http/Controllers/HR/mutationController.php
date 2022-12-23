<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//model
use App\Models\HR\Mutation;
use App\Models\HR\Export\ExportMutation;

//lib
use DB;
use DataTables;
use Excel;
use Carbon\Carbon;

class mutationController extends Controller
{
    //export
    public function exportMutationToExcel(){
        $dateTime=Carbon::now()->format('d-m-Y H.i');
        $fileName="Riwayat Mutasi Karyawan per  ".$dateTime.".xlsx";
        return Excel::download(new ExportMutation, $fileName);
    }
    //datatable
    public function mutationTable(Request $request){
        $datas = [];
        if($request->ajax()) {
            $datas = Mutation::query();
        }
        return Datatables::of($datas)->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hr.mutation');
    }
}
