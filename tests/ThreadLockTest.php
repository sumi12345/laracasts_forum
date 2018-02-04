<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadLockTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        // $this->disableExceptionHandling();
    }

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->json('post', $thread->path().'/lock');

        $this->seeStatusCode(403);
    }

    /** @test */
    public function administrators_can_lock_threads()
    {
        $admin = create('App\User', ['name' => 'JohnDoe']);
        $thread = create('App\Thread');
        $this->signIn($admin);

        $this->json('post', $thread->path().'/lock');

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function a_locked_thread_can_not_add_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $thread->lock();
        $this->assertTrue($thread->fresh()->locked);

        $this->post($thread->path().'/replies', [
            'body' => 'a_locked_thread_can_not_add_replies',
        ]);
        $this->seeStatusCode(422);
    }
}
