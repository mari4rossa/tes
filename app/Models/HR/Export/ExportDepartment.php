<?php

namespace App\Models\HR\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\HR\Department;

class ExportDepartment implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Department::selectRaw("department_code, department_name,
            CASE
                WHEN active = 0 THEN 'Tidak aktif'
                WHEN active = 1 THEN 'Aktif'
            END AS active")->get();
    }
    
    public function headings() : array
    {
        return ["Kode Divisi", "Nama Divisi", "Status"];
    }

}
?>