<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'epfno',
        'full_name',
        'call_name',
        'gender',
        'contact_no',
        'shift',
        'activation',
        'photo',
        'department_id',
        'designation_id',
        'company_id',
        'location_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function departments(){
        return $this->belongsTo(Department::class, 'department_id', 'id');         
    }

    public function designations(){
        return $this->belongsTo(Designation::class, 'designation_id', 'id');         
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
