<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerGroup extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'name',
        'code',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }  
}
