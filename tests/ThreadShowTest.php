<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadShowTest extends TestCase
{
    /** @test */
    public function a_user_can_browse_a_single_thread()
    {
        // 帖子详情页面可以看到帖子标题
        $thread = create('App\Thread');

        $this->visit($thread->path())->see($thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // 帖子详情页面可以看到最新评论内容
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->visit($thread->path())->see($reply->body);
    }

    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        // 访问帖子详情页面, 点击数 + 1
        \App\Trending::reset();

        $thread = create('App\Thread');

        $this->call('GET', $thread->path());

        $this->assertCount(1, \App\Trending::get());
    }
}
