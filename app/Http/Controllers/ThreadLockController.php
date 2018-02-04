<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Thread;

class ThreadLockController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function store($channel, Thread $thread)
    {
        $thread->lock();
    }
}
