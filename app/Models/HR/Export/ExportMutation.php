<?php

namespace App\Models\HR\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

use App\Models\HR\Mutation;

class ExportMutation implements FromCollection, WithHeadings, WithColumnFormatting
{
    public function collection()
    {
        $datas = Mutation::select('nik','name','email','old_position','new_position','old_department','new_department', 'start_date')
                            ->orderBy('start_date','asc')
                            ->get();
        // dd($datas);

        // foreach($datas as $data){
        //     $date = strtotime($data->start_date->format('d-m-Y'));
        //     $formatted_date = date('d/m/Y', $date);
        //     // $data->start_date = Date_Format($date,'d-m-Y');
        //     $data->start_date = $formatted_date;
        // }
        // $sheet->setColumnFormat(array(
        //     'H' => 'dd-mm-yyyy'
        // ));

        return $datas;
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DMYMINUS
        ];
    }
    
    public function headings() : array
    {
        return ["NIK", "Nama", "Email","Jabatan Lama","Divisi Lama", "Jabatan Baru","Divisi Baru", "Tanggal Mutasi"];
    }

}
?>