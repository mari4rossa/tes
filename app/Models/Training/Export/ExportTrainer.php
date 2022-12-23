<?php

namespace App\Models\Training\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\Training\Trainer;

class ExportTrainer implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Trainer::select('trainer_code','name','email')->where('active', 1)->get();
    }
    
    public function headings() : array
    {
        return ["Kode","Nama", "Email"];
    }

}
?>