<?php

namespace App\Models\HR\Import;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Models\HR\Department;

class ImportDepartment implements ToModel, WithStartRow
{
    public function model(array $row){
        // if($row[0]!='' && $row[1]!=''){
        //     $datas = Department::where('department_code','=',$row[0]);
        //     if($datas==null){
        //         return new Department([
        //             'department_code' => $row[0],
        //             'department_name' => $row[1],
        //         ]);
        //     }
        // }
        // $checkDepartment = Department::where('department_code','=',$row[0])->get();
        // if($checkDepartment==null){
        //     return new Department([
        //         'department_code' => $row[0],
        //         'department_name' => $row[1],
        //     ]);
        // }
        return new Department([
            'department_code' => $row[0],
            'department_name' => $row[1],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
?>