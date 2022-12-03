<?php

namespace Larashop\Models\Rotary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MrnLineRotary extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'mrn_header_rotary_id',        
        'work_order_header_id',
        'workorder_no',                      
        'size',
        'quantity',
        'theoretical_usage_mtr',  
        'waste_mtr',                      
        'line_useage_mtr',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function mrn_header_rotaries(){
        return $this->belongsTo(MrnHeaderRotary::class,'mrn_header_rotary_id');
    }

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    } 
}
