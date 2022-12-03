<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machine extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'machin_number',
        'asset_number',
        'no_of_colour_front',
        'no_of_colour_back',
        'rpm',
        'photo',
        'isActive',
        'department_id',
        'machine_type_id',
        'machine_category_id',
        'wheel_type_id',
        'condition_id',
        'company_id',
        'location_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function departments(){
        return $this->belongsTo(Department::class, 'department_id', 'id');         
    }

    public function machine_types(){
        return $this->belongsTo(MachineType::class, 'machine_type_id', 'id');         
    }

    public function machine_categories(){
        return $this->belongsTo(MachineCategory::class, 'machine_category_id', 'id');         
    }

    public function wheel_types(){
        return $this->belongsTo(WheelType::class, 'wheel_type_id', 'id');         
    }

    public function conditions(){
        return $this->belongsTo(Condition::class, 'condition_id', 'id');         
    }

    public function companies(){
        return $this->belongsTo(Company::class, 'company_id', 'id');         
    }

    public function locations(){
        return $this->belongsTo(Location::class, 'location_id', 'id');         
    }

    public function cylinders(){
        return $this->belongsToMany(Cylinder::class);
    } 
    
    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }  
}
