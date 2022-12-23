<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $table = "trainers";
    protected $fillable = [
        'id',
        'trainer_code',
        'name',
        'email',
        'active',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function trainings(){
        return $this->hasMany('App\Models\Training\Training', 'trainer_id', 'id');
    }
    public function trainingHistories(){
        return $this->hasMany('App\Models\Training\TrainingHistory', 'trainer_id', 'id');
    }
}
