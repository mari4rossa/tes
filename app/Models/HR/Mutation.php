<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    protected $table = "mutations";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'employee_id',
        'nik',
        'name',
        'email',
        'old_position',
        'new_position',
        'old_department',
        'new_department',
        'start_date',
        'created_at',
    ];
    protected $dates = [
        'start_date',
        'created_at',
    ];
    public function employee(){
        return $this->belongsTo('App\Models\HR\Employee', 'employee_id', 'id');
    }
}
