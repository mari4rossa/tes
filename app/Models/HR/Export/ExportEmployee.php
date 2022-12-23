<?php

namespace App\Models\HR\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\HR\Employee;
use App\Models\HR\Position;
use App\Models\HR\Department;

class ExportEmployee implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Employee::join('departments','department_id','=','departments.id')
                            ->join('positions','position_id','=','positions.id')
                            ->select('nik','name','email','position_name','department_name')->where('employees.active','=',1)
                            ->orderBy('department_name','asc')
                            ->get();
    }
    
    public function headings() : array
    {
        return ["NIK", "Nama", "Email","Jabatan","Divisi"];
    }
}
?>