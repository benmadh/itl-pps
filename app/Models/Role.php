<?php

namespace Larashop\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role 
{
    // use EntrustRoleTrait;

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
