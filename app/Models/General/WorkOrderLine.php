<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrderLine extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'work_order_header_id',
        'workorder_no',
        'line_no', 
        'size',             
        'quantity',
        'barcode', 
        'article',
        'price',
        'upos_required', 
        'product_grp', 
        'company_chain', 
        'year_season', 
        'source_code',
        'chest_literal',
        'new_stock_room_id', 
        'wo_height', 
        'european_size',
        'dept_code', 
        'style_code', 
        'colour_descr', 
        'colour_code',
        'sku', 
        'description_print_label', 
        'garment_colour', 
        'upc_no', 
        'pack_qty',
        'date_code', 
        'cup',
        'pack_code', 
        'pack_ean_code',   
        'status_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function work_order_headers(){
        return $this->belongsTo(WorkOrderHeader::class,'work_order_header_id');
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
}
