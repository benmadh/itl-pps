<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanBaseTable extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'date',
        'freeze_status',
        'department_id',
        'company_id',
        'location_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function departments(){
        return $this->belongsTo(Department::class, 'department_id', 'id');         
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
