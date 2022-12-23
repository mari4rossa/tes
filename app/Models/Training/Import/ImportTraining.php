<?php

namespace App\Models\Training\Import;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Models\Training\Trainer;
use App\Models\Training\Training;

class ImportTraining implements ToModel, WithStartRow
{
    public function model(array $row){
        // $trainer = Trainer::where('trainer_code','=',$row[1])->first();
        // $trainer_id = $trainer->id;
        // $checkTraining = Training::where('training_name','=',$row[0])->where('trainer_id','=',$trainer_id);
        // if($checkTraining){
        //     return new Training([
        //         'training_name' => $row[0],
        //         'trainer_id' => $trainer_id,
        //     ]);
        // }
        $trainer = Trainer::where('trainer_code','=',$row[1])->first();
        $trainer_id = $trainer->id;
        return new Training([
            'training_name' => $row[0],
            'trainer_id' => $trainer_id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
?>