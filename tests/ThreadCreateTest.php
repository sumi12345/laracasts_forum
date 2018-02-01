<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadCreateTest extends TestCase
{
    /** @test */
    public function an_unauthenticated_user_can_not_create_threads()
    {
        // 用户不登录时发表帖子
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        // 应该跳转到登录页面
        $this->assertRedirectedTo('auth/login');
    }

    /** @test */
    public function users_have_not_confirmed_eamil_can_not_create_threads()
    {
        $user = create('App\User', ['confirmed' => false]);
        $this->signIn($user);

        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        $this->assertRedirectedTo('/threads');
        $this->assertSessionHas('alert_flash', '验证邮箱');
    }

    /** @test */
    public function an_authenticated_user_can_create_threads()
    {
        // 用户登录
        $this->signIn(create('App\User'));

        // 用户登录时发表帖子
        $thread = make('App\Thread', ['channel_id' => 2]);
        $this->post('/threads', $thread->toArray());

        // 全部帖子中看到这个帖子
        $this->visit('/threads')->see($thread->title);
    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page() {
        // 用户不登录时访问创建帖子页面 应跳转到登录页面
        // 不能用 visit 因为它会跟踪跳转
        $this->get('/threads/create')->assertRedirectedTo('auth/login');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirectedTo('auth/login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertResponseStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread', ['user_id' => $user->id]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete($thread->path(), [], ['Accept' =>  'application/json'])
            ->notSeeInDatabase('threads', ['id' => $thread->id])
            ->notSeeInDatabase('replies', ['id' => $reply->id])
            ->notSeeInDatabase('activities', [
                'subject_id' => $thread->id,
                'subject_type' => get_class($thread)
            ])
            ->notSeeInDatabase('activities', [
                'subject_id' => $reply->id,
                'subject_type' => get_class($reply)
            ]);

        $this->assertResponseStatus(204);
    }

    // 工具方法 发布帖子
    public function publishThread($overrides = [])
    {
        $this->signIn();
        $thread = make('App\Thread', $overrides);
        $this->post('/threads', $thread->toArray());

        return $this;
    }
}
