<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrderHeader extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'workorder_no',            
        'main_workorder_id',
        'main_workorder_no',
        'same_as_status',
        'same_as_felds',
        'customer_order_no',
        'po_number',
        'sales_order_no',
        'order_date',
        'delivery_date',          
        'po_date',
        'pcu_date',
        'mwo_delivery_date',
        'delivery_address',
        'wo_quantity',
        'wo_value',
        'lenght',
        'width',
        'size_changes',
        'no_of_colors_front',
        'no_of_colors_back',
        'print_colors',
        'ground_shade',
        'fold_type',
        'material_type',
        'hold_status',
        'hold_reason',
        'remarks',
        'chain_id',
        'customer_group_id',           
        'customer_id',
        'label_reference_id',
        'department_id',
        'order_type_id',
        'label_type_id',
        'status_id',
        'chain_id_lv',
        'print_status_id',
        'cutting_status_id',
        'verifications_status_id',
        'packing_status_id',
        'aql_status_id',
        'despatch_status_id',
        'mrn_print_status_id',
        'mrn_issue_status_id',
        'substrate_category_id',
        'substrate_category',
        'cutting_method_id',
        'size_id', 
        'line_item', 
        'item_code',     
        'style_no',          
        'prd_image',
        'prd_type', 
        'prd_code', 
        'prd_description',         
        'wo_quality',
        'wo_collection',
        'vsd', 
        'vss', 
        'rn', 
        'ca', 
        'dpci',             
        'contract_code', 
        'ms_dept', 
        'series_no', 
        'stroke_no',           
        'supplier_code', 
        'style_descr', 
        'po_code', 
        'department', 
        'country_code', 
        'country_of_origin', 
        'factory_id',
        'date_of_mrf', 
        'silhouette', 
        'time_batch_size_upd',
        'no_of_sizes_after_batch',
        'standard_produce_time',
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'location_id',
    ]; 

    public function work_order_lines() {        
        return $this->hasMany(WorkOrderLine::class, 'work_order_header_id')->where('deleted_at', null);
    }

    public function cutting_methods(){
        return $this->belongsTo(CuttingMethod::class, 'cutting_method_id', 'id');
    }
    
    public function chains(){
        return $this->belongsTo(Chain::class, 'chain_id', 'id');
    }

    public function customer_groups(){
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id', 'id');         
    }

    public function customers(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');         
    }   

    public function label_references(){
        return $this->belongsTo(LabelReference::class, 'label_reference_id', 'id');         
    }

    public function departments(){
        return $this->belongsTo(Department::class, 'department_id', 'id');         
    }

    public function order_types(){
        return $this->belongsTo(OrderType::class, 'order_type_id', 'id');         
    }

    public function label_types(){
        return $this->belongsTo(LabelType::class, 'label_type_id', 'id');         
    }

    public function statuses(){
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }    

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    } 

    public function companies(){
        return $this->belongsTo(Company::class, 'company_id', 'id');         
    }

    public function locations(){
        return $this->belongsTo(Location::class, 'location_id', 'id');         
    }
}
