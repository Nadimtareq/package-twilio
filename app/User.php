<?php

namespace App;

use Alert;
use Redirect;
use App\Models\UserBan;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'reference_code', 'lang'];
    protected $hidden = ['password', 'remember_token'];

    public static function getRoleErrorPopup() 
    {
        alert()->error('', 'U heeft niet de bevoegde rechten om deze pagina te bezoeken')->persistent('Sluiten');
    }

    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'user_id');
    }

    public function companiesWaiter()
    {
        return $this->hasMany('App\Models\Company', 'waiter_user_id');
    }

    public static function banned($userId)
    {
        $banned = UserBan::where('user_id', $userId)
            ->where('expired_date', '>=', date('Y-m-d'))
            ->get()
            ->toArray()
        ;

        return $banned;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deals(){
        return $this->hasMany('App\Models\FutureDeal');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_users');
    }

    public function scopeHasRole($query, $role)
    {
        $query->whereHas('roles', function ($query) use ($role) {
            $query->where('role', $role);
        });
    }
}
