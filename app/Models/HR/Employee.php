<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = "employees";
    protected $fillable = [
        'id',
        'nik',
        'name',
        'email',
        'position_id',
        'department_id',
        'active',
        'entry_date',
        'out_date',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'entry_date',
        'out_date',
        'created_at',
        'updated_at',
    ];
    public function position(){
        return $this->belongsTo('App\Models\HR\Position', 'position_id', 'id');
    }
    public function department(){
        return $this->belongsTo('App\Models\HR\Department', 'department_id', 'id');
    }
    public function mutations(){
        return $this->hasMany('App\Models\HR\Mutation', 'employee_id', 'id');
    }
    public function trainingHistories(){
        return $this->hasMany('App\Models\Training\TrainingHistory', 'employee_id', 'id');
    }
}
