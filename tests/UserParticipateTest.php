<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserParticipateTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
        $this->user = factory('App\User')->create();
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum()
    {
        // 用户登录
        $this->be($this->user);

        // 用户回复帖子
        $reply = factory('App\Reply')->make();
        $this->post('/threads/'.$this->thread->id.'/replies', $reply->toArray());

        // 帖子详情可以看到这条回复
        $this->visit('/threads/'.$this->thread->id)->see($reply->body);
    }

    /** @test */
    public function an_unauthenticated_user_may_not_add_replies()
    {
        // 用户不登录时回复帖子
        $reply = factory('App\Reply')->make();
        $this->post('/threads/'.$this->thread->id.'/replies', $reply->toArray());

        // 帖子详情不能看到这条回复
        $this->visit('/threads/'.$this->thread->id)->dontSee($reply->body);
    }

}
