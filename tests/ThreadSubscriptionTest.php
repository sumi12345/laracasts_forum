<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadSubscriptionTest extends TestCase
{
    /** @test */
    public function a_user_can_subscribe_to_threads() {
        $thread = create('App\Thread');
        $this->signIn();

        $this->post($thread->path().'/subscriptions');

        $this->seeInDatabase('thread_subscriptions', [
            'thread_id' => $thread->id,
            'user_id' => auth()->id()
        ]);
    }
}
