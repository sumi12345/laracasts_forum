<?php

namespace App\Policies;

use App\Thread;
use App\User;

class ThreadPolicy
{
    public function update(User $user, Thread $thread)
    {
        return $thread->user_id == $user->id;
    }
}
