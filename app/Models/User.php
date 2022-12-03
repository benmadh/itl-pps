<?php

namespace Larashop\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notifiable;
//use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Larashop\Notifications\LarashopAdminResetPassword as ResetPasswordNotification;
use Larashop\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Larashop\Models\General\Location;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name',
       'user_name', 
       'email', 
       'password',
       'locations_id',
       'created_by',
       'updated_by',
       'deleted_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'password', 
       'remember_token',
    ];

    public function getAvatarUrl(){
       return "https://www.gravatar.com/avatar/" . md5($this->email) . "?d=mm";        
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    } 

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    }

    public function locations(){
        return $this->belongsTo(Location::class, 'locations_id', 'id');         
    }
}
