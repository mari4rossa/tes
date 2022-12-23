<?php

namespace App\Models\Training\Import;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Models\Training\Trainer;

class ImportTrainer implements ToModel, WithStartRow
{
    public function model(array $row){
        // $checkTrainer = Trainer::where('trainer_code','=',$row[0]);
        // if($checkTrainer==null){
        //     return new Trainer([
        //         'trainer_code' => $row[0],
        //         'name' => $row[1],
        //         'email' => $row[2],
        //     ]);
        // }
        return new Trainer([
            'trainer_code' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
        ]);
    }
        
    public function startRow(): int
    {
        return 2;
    }

}
?>