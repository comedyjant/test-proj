<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Content;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasPermissions('admin-manage-content')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the content.
     *
     * @param  App\User  $user
     * @param  App\Content  $content
     * @return mixed
     */
    public function view(User $user, Content $content)
    {
        //
    }

    /**
     * Determine whether the user can create contents.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the content.
     *
     * @param  App\User  $user
     * @param  App\Content  $content
     * @return mixed
     */
    public function update(User $user, Content $content)
    {
        //
    }

    /**
     * Determine whether the user can delete the content.
     *
     * @param  App\User  $user
     * @param  App\Content  $content
     * @return mixed
     */
    public function delete(User $user, Content $content)
    {
        //
    }
}
