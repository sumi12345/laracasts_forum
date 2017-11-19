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
    public function an_authenticated_user_can_create_threads()
    {
        // 用户登录
        $this->signIn();

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

    // 工具方法 发布帖子
    public function publishThread($overrides = [])
    {
        $this->signIn();
        $thread = make('App\Thread', $overrides);
        $this->post('/threads', $thread->toArray());

        return $this;
    }
}
