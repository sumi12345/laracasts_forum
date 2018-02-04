<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadLockTest extends TestCase
{
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
