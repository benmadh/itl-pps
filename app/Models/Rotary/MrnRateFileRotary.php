<?php

namespace Larashop\Models\Rotary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Larashop\Models\General\Company;
use Larashop\Models\General\Location;
use Larashop\Models\General\MachineType;
use Larashop\Models\General\Department;

class MrnRateFileRotary extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'company_id',
        'location_id',
        'machine_type_id',
        'table_type',
        'isActive',
        'each_clr_front_waste_mtr',
        'each_clr_front_setup_time_min',
        'each_clr_back_waste_mtr',
        'each_clr_back_setup_time_min',
        'plate_change_waste_mtr',
        'plate_change_setup_time_min',
        'tape_setup_waste_mtr',
        'tape_setup_setup_time_min',
        'reference_change_time_min',
        'department_id',
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

    public function machine_types(){
        return $this->belongsTo(MachineType::class, 'machine_type_id', 'id');         
    } 

    public function additionsCusRotaries() {        
        return $this->hasMany(AdditionsToCusRotary::class, 'mrn_rate_file_rotaries_id')->where('deleted_at', null);
    } 
    
    public function cuttingWasteRotaries() {        
        return $this->hasMany(CuttingWasteRotary::class, 'mrn_rate_file_rotaries_id')->where('deleted_at', null);
    }

    public function runningWasteRotaries() {        
        return $this->hasMany(RunningWasteRotary::class, 'mrn_rate_file_rotaries_id')->where('deleted_at', null);
    } 

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }  
}
