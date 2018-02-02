<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Reply;

class ReplyBestController extends Controller
{
    /**
     * @param \App\Reply $reply
     */
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->update(['best_reply_id' => $reply->id]);
    }
}
