<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadTest extends TestCase
{
    // 在测试开始时迁移, 结束后销毁
    //use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        // 全部帖子页面可以看到最新帖子的标题
        $this->visit('/threads')->see($this->thread->title);
    }

    /** @test */
    public function a_user_can_browse_a_single_thread()
    {
        // 帖子详情页面可以看到帖子标题
        $this->visit('/threads/'.$this->thread->id)->see($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // 帖子详情页面可以看到最新评论内容
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        $this->visit('/threads/'.$this->thread->id)->see($reply->body);
    }
}
