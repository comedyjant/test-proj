<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quiz;
use App\Models\WlaCourse;
use App\Models\WlaCourseSection;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizzesPolicy
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

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Quiz $quiz) {
        return $quiz->content->user_id == $user->id;
    }

    public function delete(User $user, Quiz $quiz) {
        return $quiz->content->user_id == $user->id;
    }

}
