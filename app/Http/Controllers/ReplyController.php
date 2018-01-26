<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;
use App\Thread;
use App\Http\Requests\CreateReplyRequest;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $channel_slug
     * @param \App\Thread $thread
     * @param \App\Http\Requests\CreateReplyRequest $form
     * @return \Illuminate\Http\Response
     */
    public function store($channel_slug, Thread $thread, CreateReplyRequest $form)
    {
        // 引入 CreateReplyRequest, 自动执行 Form Request Validation
        // validation 不通过会 throw ValidationException
        // 返回 422, {body: ["The body contains spam."]}
        // authorize 不通过会 throw AuthorizationException, 定义在 failedAuthorization()
        // 返回 403, forbidden
        // 403 提示太笼统, 可以自定义 ThrottleException, 并在 Handler 中处理

        // 只通过 json 访问, 直接返回 json
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
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
