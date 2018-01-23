<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ThreadSubscriptionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => []]);
    }

    /**
     * @param \App\Channel $channel
     * @param \App\Thread $thread
     */
    public function store($channel, $thread)
    {
        $thread->subscribe();
    }

    /**
     * @param \App\Channel $channel
     * @param \App\Thread $thread
     */
    public function destroy($channel, $thread)
    {
        $thread->unsubscribe();
    }
}
