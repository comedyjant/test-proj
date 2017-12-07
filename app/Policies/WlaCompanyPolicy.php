<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WlaCompany;
use Illuminate\Auth\Access\HandlesAuthorization;

class WlaCompanyPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if($user->isSuperAdmin() || $user->hasPermissions('admin-manage-companies')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the wlaCompany.
     *
     * @param  \App\User  $user
     * @param  \App\WlaCompany $wlaCompany
     * @return mixed
     */
    public function view(User $user, WlaCompany $wlaCompany)
    {
        //
    }

    /**
     * Determine whether the user can create wlaCompanies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the wlaCompany.
     *
     * @param  \App\User  $user
     * @param  \App\WlaCompany $wlaCompany
     * @return mixed
     */
    public function update(User $user, WlaCompany $wlaCompany)
    {
        return $wlaCompany->userIsAdmin($user);
    }

    /**
     * Determine whether the user can delete the wlaCompany.
     *
     * @param  \App\User  $user
     * @param  \App\WlaCompany $wlaCompany
     * @return mixed
     */
    public function delete(User $user, WlaCompany $wlaCompany)
    {
        //
    }
}
