<?php

namespace Larashop\Models;

use Zizaco\Entrust\EntrustPermission;
use Zizaco\Entrust\Traits\EntrustPermissionTrait;

class Permission
{
    use EntrustPermissionTrait;

    protected $fillable = [
	    'name',    
	    'display_name', 
	    'description', 
	    'created_by',
        'updated_by',
    ];

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }
}
