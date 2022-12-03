<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabelReference extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'name',
        'description',
        'ground_colour',
        'print_colour',
        'combo',
        'default_lenght',
        'default_width',
        'file_name',
        'chain_id',
        'labeltype_id',
        'department_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function chains(){
        return $this->belongsTo(Chain::class, 'chain_id', 'id');         
    }

    public function departments(){
        return $this->belongsTo(Department::class, 'department_id', 'id');         
    }

    public function labelTypes(){
        return $this->belongsTo(LabelType::class, 'labeltype_id', 'id');         
    }

    public function itemLabelReferences(){
        return $this->hasMany(ItemLabelReference::class, 'label_reference_id', 'id'); 
    }
    
    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    } 
}
