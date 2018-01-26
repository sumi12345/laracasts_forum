<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserParticipateTest extends TestCase
{
    protected $thread;

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
        $this->post($this->thread->path().'/replies', $reply->toArray());

        // 应该跳转到登录页面
        $this->assertRedirectedTo('auth/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum()
    {
        // 用户登录
        $this->signIn($this->user);

        // 用户登录时回复帖子
        $reply = make('App\Reply');
        $this->post($this->thread->path().'/replies', $reply->toArray());

        // 帖子详情可以看到这条回复
        $this->visit($this->thread->path())->see($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn();
        $reply = make('App\Reply', ['body' => null]);
        $this->post($this->thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        // 未登录用户
        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirectedTo('/auth/login');

        // 已登录用户但不是作者
        $this->signIn();

        $this->delete("/replies/{$reply->id}")
            ->assertResponseStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")
            ->notSeeInDatabase('replies', ['id' => $reply->id])
            ->seeStatusCode(200);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        // 未登录用户
        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirectedTo('/auth/login');

        // 已登录用户但不是作者
        $this->signIn();

        $this->patch("/replies/{$reply->id}")
            ->assertResponseStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $new_body = 'You been changed.';

        $this->patch("/replies/{$reply->id}", ['body' => $new_body])
            ->seeInDatabase('replies', ['id' => $reply->id, 'body' => $new_body]);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Suppport'
        ]);
        $this->signIn($reply->owner);

        $this->json('post', $reply->thread->path().'/replies', $reply->toArray());
        $this->seeStatusCode(422);
    }

    /** @test */
    public function users_may_only_reply_once_per_minute()
    {
        $reply = make('App\Reply');
        $thread = create('App\Thread');
        $this->signIn();

        $this->json('post', $thread->path().'/replies', $reply->toArray())
            ->seeStatusCode(200);

        $this->json('post', $thread->path().'/replies', $reply->toArray())
            ->seeStatusCode(429);
    }
}
