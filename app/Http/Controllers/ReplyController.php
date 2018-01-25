<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;
use App\Thread;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $channel_slug
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store($channel_slug, Thread $thread)
    {
        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);

            if (request()->wantsJson()) {
                return $reply->load('owner');
            }

            return redirect($thread->path());
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
        try {
            $this->authorize('update', $reply);

            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(['body' => request('body')]);

            return response(['status' => 'Reply updated!']);
        } catch (\Exception $e) {
            $message = 'Sorry, your reply could not be saved at this time.';
            return response($message, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        return response(['status' => 'Reply deleted!']);
    }

}
