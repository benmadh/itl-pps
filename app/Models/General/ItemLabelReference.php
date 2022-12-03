<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemLabelReference extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'label_reference_id',
        'item_id',
        'unit_price',
        'quantity',
        'unit_value',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function items(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function labelReferences(){
        return $this->belongsTo(LabelReference::class, 'label_reference_id', 'id');
    }
    
    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    } 
}
