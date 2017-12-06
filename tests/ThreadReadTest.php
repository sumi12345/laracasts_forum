<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadReadTest extends TestCase
{
    // 在测试开始时迁移, 结束后销毁
    //use DatabaseMigrations;

    protected $thread;

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
        $this->visit($this->thread->path())->see($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // 帖子详情页面可以看到最新评论内容
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        $this->visit($this->thread->path())->see($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = factory('App\Channel')->create();
        $threadInChannel = factory('App\Thread')->create(['channel_id' => $channel->id]);
        $threadNotInChannel = factory('App\Thread')->create();

        //$this->get('/threads/'.$channel->slug)
          //  ->see($threadInChannel->title)
            //->dontSee($threadNotInChannel->title);
    }
}
