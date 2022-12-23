<?php

namespace App\Models\HR\Import;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Models\HR\Department;
use App\Models\HR\Position;
use App\Models\HR\Employee;

use Carbon\Carbon;

class ImportEmployee implements ToModel, WithStartRow
{
    public function model(array $row){
        // $checkEmployee = Employee::where('nik','=',$row[0]);
        // if($checkEmployee==null){
        //     $position = Position::where('position_name','=',$row[3])->first();
        //     $position_id = $position->id;

        //     $department = Department::where('department_name','=',$row[4])->first();
        //     $department_id = $department->id;

        //     $date = $row[5];
        //     dd($row[5]);
        //     $entry_date = Carbon::parse($date)->format('Y-m-d');

        //     return new Employee([
        //         'nik' => $row[0],
        //         'name' => $row[1],
        //         'email' => $row[2],
        //         'position_id' => $position_id,
        //         'department_id' => $department_id,
        //         'entry_date'=> $entry_date
        //     ]);
        // } 

        $position = Position::where('position_name','=',$row[3])->first();
            $position_id = $position->id;

            $department = Department::where('department_name','=',$row[4])->first();
            $department_id = $department->id;

            $date = $row[5];
            $entry_date = Carbon::parse($date)->format('Y-m-d');
        return new Employee([
            'nik' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'position_id' => $position_id,
            'department_id' => $department_id,
            'entry_date'=> $entry_date
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
?>