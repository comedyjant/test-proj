<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WlaClassroomCourse;
use Illuminate\Auth\Access\HandlesAuthorization;

class WlaClassroomCoursePolicy
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

    /**
     * Determine whether the user can view the wlaClassroomCourse.
     *
     * @param  \App\User  $user
     * @param  \App\WlaClassroomCourse  $wlaClassroomCourse
     * @return mixed
     */
    public function view(User $user, WlaClassroomCourse $wlaClassroomCourse)
    {
        return $wlaClassroomCourse->user_id == $user->id;
    }

    /**
     * Determine whether the user can create wlaClassroomCourses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the wlaClassroomCourse.
     *
     * @param  \App\User  $user
     * @param  \App\WlaClassroomCourse  $wlaClassroomCourse
     * @return mixed
     */
    public function update(User $user, WlaClassroomCourse $wlaClassroomCourse)
    {
        return $wlaClassroomCourse->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the wlaClassroomCourse.
     *
     * @param  \App\User  $user
     * @param  \App\WlaClassroomCourse  $wlaClassroomCourse
     * @return mixed
     */
    public function delete(User $user, WlaClassroomCourse $wlaClassroomCourse)
    {
        return $wlaClassroomCourse->user_id == $user->id;
    }
}
