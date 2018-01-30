<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Thread;
use App\Filters\ThreadFilter;
use App\Channel;
use Log;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Channel $channel
     * @param \App\Filters\ThreadFilter $filter
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilter $filter)
    {
        // 定义 threads 变量
        $threads = Thread::with('channel')->orderBy('id', 'desc');

        // 筛选频道
        if ($channel->exists) {
            $threads = $channel->threads();
        }

        // 筛选 query 条件
        $threads = $threads->filter($filter);

        //dd($threads->toSql());
        $threads = $threads->get();

        // json 返回
        if (request()->wantsJson()) return $threads;

        // 阅读数最高
        $trending = array_map(function ($thread) {
            return json_decode($thread);
        }, \Redis::zrevrange('trending_threads', 0, 4));

        return view('threads.index', compact('threads', 'trending'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
        ]);

        Log::debug('ThreadController@store add thread: '. request('title'));

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        return redirect($thread->path())
            ->with('alert_flash', '帖子发布成功!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Channel $channel
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread)
    {
        $replies = $thread->replies()->paginate(5);
        $params = [
            'thread' => $thread,
            'replies' => $replies->toArray()['data'],
            'replies_link' => $replies->render()
        ];

        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        // 阅读数 + 1
        \Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]));

        return view('threads.show', $params);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Channel $channel
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }
}
