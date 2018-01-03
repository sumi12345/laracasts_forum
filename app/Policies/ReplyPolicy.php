<?php

namespace App\Policies;

use App\Reply;
use App\User;

class ReplyPolicy
{
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }
}
