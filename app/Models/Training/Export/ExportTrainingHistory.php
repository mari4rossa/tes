<?php

namespace App\Models\Training\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\Training\TrainingHistory;

class ExportTrainingHistory implements FromCollection, WithHeadings
{
    public function collection()
    {
        $datas = TrainingHistory::select('nik','name','email', 'position_name', 'department_name',
                                        'training_name','trainer_code', 'trainer_name', 'trainer_email',
                                        'start_date','end_date')
                                        ->get();
        
        // $datas = TrainingHistory::selectRaw("nik, name, email, position_name, department_name, training_name, trainer_code, trainer_name, trainer_email, DATE_FORMAT(start_date, '%d-%m-%Y') AS start_date, DATE_FORMAT(end_date, '%d-%m-%Y') AS end_date")
        //                                 ->get();
        return $datas;
    }
    
    public function headings() : array
    {
        return [
            "NIK","Nama Karyawan", "Email Karyawan", "Jabatan", "Divisi", 
            "Program Pelatihan", "Kode Pelatih", "Nama Pelatih", "Email Pelatih",
            "Tanggal Mulai", "Tanggal Selesai"
        ];
    }
}
?>