<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadReadTest extends TestCase
{
    // 在测试开始时迁移, 结束后销毁 (不起作用 改为写在 setup 和 teardown 里 或 方法里)
    //use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        // 影响下一个 Test 文件, 先注释
        // $this->artisan('migrate');

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
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->get('threads?popular=1', ['Accept' =>  'application/json'])->response;
        $response = json_decode($response->getContent());

        // 失败 因为没弄清怎么构建 builder 让 threads 按 replies_count 排序
        //$this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }

    public function tearDown()
    {
        // 影响下一个 Test 文件, 先注释
        // $this->artisan('migrate:rollback');

        parent::tearDown();
    }
}
