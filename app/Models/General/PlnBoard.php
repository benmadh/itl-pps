<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlnBoard extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'pln_date',
        'pln_shift',
        'pln_mnt',
        'pln_qty',
        'wo_tot_mnt',
        'wo_tot_qty',
        'plan_type',
        'machine_id',
        'work_order_header_id',       
        'company_id',
        'location_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function work_order_headers(){
        return $this->belongsTo(WorkOrderHeader::class,'work_order_header_id');
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
