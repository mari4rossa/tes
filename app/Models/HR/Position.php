<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = "positions";
    protected $fillable = [
        'id',
        'position_name',
        'department_id',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function department(){
        return $this->belongsTo('App\Models\HR\Department', 'department_id', 'id');
    }
    public function employees(){
        return $this->hasMany('App\Models\HR\Employee', 'position_id', 'id');
    }
    public function trainingHistories(){
        return $this->hasMany('App\Models\Training\TrainingHistory', 'position_id', 'id');
    }
}
