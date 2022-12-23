<?php

namespace App\Models\HR\Import;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Models\HR\Department;
use App\Models\HR\Position;

class ImportPosition implements ToModel, WithStartRow
{
    public function model(array $row){
        // $checkPosition = Position::where('position_name','=',$row[0]);
        // if($checkPosition==null){
        //     $department = Department::where('department_name','=',$row[1])->first();
        //     $department_id = $department->id;
        //     return new Position([
        //         'position_name' => $row[0],
        //         'department_id' => $department_id,
        //     ]);
        // }
        $department = Department::where('department_name','=',$row[1])->first();
        $department_id = $department->id;
        return new Position([
            'position_name' => $row[0],
            'department_id' => $department_id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
?>