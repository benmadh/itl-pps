<?php

namespace Larashop\Models\Rotary;

use Illuminate\Database\Eloquent\Model;
use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\MachineType;
use Larashop\Models\General\CuttingMethod;
use Illuminate\Database\Eloquent\SoftDeletes;

class MrnHeaderRotary extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [ 
        'work_order_header_id',
        'workorder_no', 
        'total_quantity',
        'date',
        'size_changes_after_batching',
        'machine_type_id',
        'table_type',
        'cutting_method_id',
        'ref_changes_min',
        'setup_time_per_tape',
        'setup_time_per_colour_front',
        'setup_time_per_colour_back',
        'setup_time_plate_change',
        'setup_mtr_per_colour_front',
        'setup_mtr_per_colour_back',
        'setup_mtr_plate_change',
        'running_waste_percentage',
        'cf_waste_pcs',
        'add_to_customer_percentage',
        'machine_speed_mrt_per_hrs',
        'no_of_tapes',
        'cal_ribbon_mtr',
        'cal_cut_fold_mtr',
        'cal_set_up_mtr',
        'cal_plate_change_mtr',
        'cal_running_waste_mtr',
        'qty_per_Size',
        'qty_per_Size_packing',
        'cal_additions_mtr',
        'total_material_issued_mtr',
        'total_time_setup_time_duration',
        'total_time_for_plate_changes',
        'cal_running_time',
        'total_standard_time_for_job',
        'total_qty_to_produce_with_wastage',
        'productivity_calc_hrs',
        'actual_time_to_Complete_job',
        'cal_performance_percentage',
        'cal_quality_percentage',
        'cal_availability_percentage',
        'cal_oee_percentage',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function work_order_headers(){
        return $this->belongsTo(WorkOrderHeader::class,'work_order_header_id');
    }

    public function mrn_line_rotaries() {        
        return $this->hasMany(MrnLineRotary::class,'mrn_header_rotary_id');
    }

    public function machine_types(){
        return $this->belongsTo(MachineType::class,'machine_type_id');
    }

    public function cutting_methods(){
        return $this->belongsTo(CuttingMethod::class,'cutting_method_id');
    }
    
    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }  
}
