<?php

namespace App\Models\HR\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\HR\Position;
use App\Models\HR\Department;

class ExportPosition implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Position::join('departments','department_id','=','departments.id')
                            ->select('position_name', 'department_code','department_name')->where('positions.active','=',1)->get();
    }
    
    public function headings() : array
    {
        return ["Jabatan", "Kode Divisi", "Nama Divisi"];
    }

}
?>