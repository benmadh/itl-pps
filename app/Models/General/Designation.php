<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'name',
        'code',
        'levels_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function levels(){
        return $this->belongsTo(Level::class, 'levels_id', 'id');         
    }
    
    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    } 
}
