<?php

namespace Larashop\Models\Rotary;

use Illuminate\Database\Eloquent\Model;
use Larashop\Models\Rotary\MrnRateFileRotary;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionsToCusRotary extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'from_ord_qty',
        'to_ord_qty',
        'percentage',
        'mrn_rate_file_rotaries_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function mrnRrateFileRotaries(){
        return $this->belongsTo(MrnRateFileRotary::class, 'mrn_rate_file_rotaries_id', 'id');
    }
    
    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }  
}
