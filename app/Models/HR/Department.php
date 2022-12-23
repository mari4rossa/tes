<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "departments";
    protected $fillable = [
        'id',
        'department_code',
        'department_name',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function positions(){
        return $this->hasMany('App\Models\HR\Position', 'department_id', 'id');
    }
    public function employees(){
        return $this->hasMany('App\Models\HR\Employee', 'department_id', 'id');
    }
    public function trainingHistories(){
        return $this->hasMany('App\Models\Training\TrainingHistory', 'department_id', 'id');
    }
}
