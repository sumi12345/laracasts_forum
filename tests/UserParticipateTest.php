<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserParticipateTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
        $this->user = create('App\User');
    }

    /** @test */
    public function an_unauthenticated_user_may_not_add_replies()
    {
        // 用户不登录时回复帖子
        $reply = make('App\Reply');
        $this->post('/threads/'.$this->thread->id.'/replies', $reply->toArray());

        // 帖子详情不能看到这条回复
        $this->visit('/threads/'.$this->thread->id)->dontSee($reply->body);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum()
    {
        // 用户登录
        $this->signIn($this->user);

        // 用户登录时回复帖子
        $reply = make('App\Reply');
        $this->post('/threads/'.$this->thread->id.'/replies', $reply->toArray());

        // 帖子详情可以看到这条回复
        $this->visit('/threads/'.$this->thread->id)->see($reply->body);
    }

    /** @test */
    public function an_unauthenticated_user_can_not_create_threads()
    {
        // 用户不登录时发表帖子
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        // 全部帖子中看不到这个帖子
        $this->visit('/threads')->dontsee($thread->title);
    }

    /** @test */
    public function an_authenticated_user_can_create_threads()
    {
        // 用户登录
        $this->signIn($this->user);

        // 用户登录时发表帖子
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        // 全部帖子中看到这个帖子
        $this->visit('/threads')->see($thread->title);
    }

}
