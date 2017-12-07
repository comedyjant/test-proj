<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WlaContent;
use Illuminate\Auth\Access\HandlesAuthorization;

class WlaContentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if($user->isSuperAdmin()) {
            return true;
        }
        if (! $user->wla) {
            return false;
        }
    }

    public function update(User $user, WlaContent $content)
    {
        return $content->user_id == $user->id;
    }

    public function delete(User $user, WlaContent $content)
    {
        return $content->user_id == $user->id;
    }
}
