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

        foreach($datas as $data){
            Date::dateTimeToExcel($data->start_date);
            // Carbon::parse($data->start_date)->format('d-m-Y');
        }

        return $datas;
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }
    
    public function headings() : array
    {
        return ["NIK", "Nama", "Email","Jabatan Lama","Divisi Lama", "Jabatan Baru","Divisi Baru", "Tanggal Mutasi"];
    }

}
?>