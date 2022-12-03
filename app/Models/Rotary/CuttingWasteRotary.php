<?php

namespace Larashop\Models\Rotary;

use Illuminate\Database\Eloquent\Model;
use Larashop\Models\General\CuttingMethod;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuttingWasteRotary extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'pcs',
        'cutting_methods_id', 
        'mrn_rate_file_rotaries_id',    
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function mrnRrateFileRotaries(){
        return $this->belongsTo(MrnRateFileRotary::class, 'mrn_rate_file_rotaries_id', 'id');
    }
    
    public function cuttingMethods(){
        return $this->belongsTo(CuttingMethod::class, 'cutting_methods_id', 'id');
    }

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }  
}
