<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HoldMachine extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'date',
        'shift',
        'mc_hold_reason_id',
        'machine_id',
        'department_id',
        'company_id',
        'location_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];    

    public function mc_hold_reasons(){
        return $this->belongsTo(McHoldReason::class, 'mc_hold_reason_id', 'id');         
    }

    public function departments(){
        return $this->belongsTo(Department::class, 'department_id', 'id');         
    }

    public function machines(){
        return $this->belongsTo(Machine::class, 'machine_id', 'id');         
    }
    
    public function companies(){
        return $this->belongsTo(Company::class, 'company_id', 'id');         
    }

    public function locations(){
        return $this->belongsTo(Location::class, 'location_id', 'id');         
    }

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    } 
}
