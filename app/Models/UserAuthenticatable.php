<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserAuthenticatable extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    use EntrustUserTrait{
       EntrustUserTrait::can as entrustCan;
       Authorizable::can insteadof EntrustUserTrait;
    }

    public function isSuperAdmin() {
        return $this->hasRole('superadmin');
    }

    public function hasPermissions($permission, $requireAll = true) {
        if($this->isSuperAdmin()) {
            return true;
        }
        return $this->entrustCan($permission, $requireAll);
    }
}
