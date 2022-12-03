<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aql extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'work_order_header_id',
        'workorder_no',
        'date',
        'shift',
        'size',
        'quantity',
        's_qty',
        'produce_size_changes',
        'insert_type',
        'employee_id',
        'department_id',
        'company_id',
        'location_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function work_order_headers(){
        return $this->belongsTo(WorkOrderHeader::class,'work_order_header_id');
    }

    public function employees(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');         
    }

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
