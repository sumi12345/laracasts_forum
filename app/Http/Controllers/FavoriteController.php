<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function store(Reply $reply)
    {
        $reply->favorite();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
