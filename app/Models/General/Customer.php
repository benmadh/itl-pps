<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'account_code',
        'name',       
        'adress_1',
        'adress_2',
        'city',
        'telephone_land',
        'telephone_fax',
        'official_email',
        'customer_group_id',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function companies(){
        return $this->belongsTo(Company::class, 'company_id', 'id');         
    }
    
    public function customerGroups(){
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id', 'id');         
    }

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }  
}
