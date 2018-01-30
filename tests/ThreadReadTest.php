<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadReadTest extends TestCase
{
    // 在测试开始时迁移, 结束后销毁 (不起作用 改为写在 setup 和 teardown 里 或 方法里)
    //use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        // 影响下一个 Test 文件, 先注释
        // $this->artisan('migrate');
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        // 全部帖子页面可以看到最新帖子的标题
        $thread = create('App\Thread');
        $this->visit('/threads')->see($thread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        // 根据频道筛选帖子
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread', ['channel_id' => create('App\Channel')->id]);

        $this->get('/threads/'.$channel->slug)
            ->see($threadInChannel->title)
            ->dontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_username()
    {
        // 根据用户筛选帖子
        $user = create('App\User');
        $this->signIn($user);

        $threadByJohn = create('App\Thread', ['user_id' => $user->id]);
        $threadNotByJohn = create('App\Thread');

        $this->get('/threads?by='.$user->name)
            ->see($threadByJohn->title)
            ->dontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        // 根据评论数给帖子排序 failed
        $this->get('threads?popular=1', ['Accept' =>  'application/json']);
        // dp(json_decode($this->response->getContent()));
    }

    public function tearDown()
    {
        // 影响下一个 Test 文件, 先注释
        // $this->artisan('migrate:rollback');

        parent::tearDown();
    }
}
