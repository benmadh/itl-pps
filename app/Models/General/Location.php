<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code',
        'name',
        'companies_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function companies(){
        return $this->belongsTo(Company::class, 'companies_id', 'id');         
    } 

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }  
}
