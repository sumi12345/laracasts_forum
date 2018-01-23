<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationTest extends TestCase
{
    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_add_a_reply() {
        $thread = create('App\Thread');
        $this->signIn();  // need to be a new user

        $thread->subscribe();
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply here',
        ]);

        $this->seeInDatabase('notifications', ['notifiable_id' => auth()->id()]);
    }
}
