<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = "trainings";
    protected $fillable = [
        'id',
        'training_name',
        'trainer_id',
        'active',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function trainer(){
        return $this->belongsTo('App\Models\Training\Trainer', 'trainer_id', 'id');
    }
    public function trainingHistories(){
        return $this->hasMany('App\Models\Training\TrainingHistory', 'training_id', 'id');
    }
}
