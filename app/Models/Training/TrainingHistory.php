<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class TrainingHistory extends Model
{
    protected $table = "training_histories";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'employee_id',
        'nik',
        'name',
        'email',
        'position_id',
        'position_name',
        'department_id',
        'department_name',
        'training_id',
        'training_name',
        'trainer_id',
        'trainer_code',
        'trainer_name',
        'trainer_email',
        'start_date',
        'end_date',
        'created_at',
    ];
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
    ];

    public function employee(){
        return $this->belongsTo('App\Models\HR\Employee', 'employee_id', 'id');
    }
    public function position(){
        return $this->belongsTo('App\Models\HR\Position', 'position_id', 'id');
    }
    public function department(){
        return $this->belongsTo('App\Models\HR\Department', 'department_id', 'id');
    }
    public function training(){
        return $this->belongsTo('App\Models\Training\Training', 'training_id', 'id');
    }
    public function trainer(){
        return $this->belongsTo('App\Models\Training\Trainer', 'trainer_id', 'id');
    }

}
