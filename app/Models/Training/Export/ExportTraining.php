<?php

namespace App\Models\Training\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\Training\Training;

class ExportTraining implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Training::join('trainers','trainer_id','=','trainers.id')
                            ->select('training_name','trainer_code','name')->where('trainings.active', 1)->get();
    }
    
    public function headings() : array
    {
        return ["Program Pelatihan","Kode Pelatih", "Nama Pelatih"];
    }

}
?>