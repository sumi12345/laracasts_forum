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
            'user_id' => create('App\User')->id,
            'body' => 'some reply here',
        ]);

        $this->seeInDatabase('notifications', ['notifiable_id' => auth()->id()]);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $thread = create('App\Thread');
        $user = create('App\User');
        $this->signIn($user);  // need to be a new user

        $thread->subscribe();
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply here',
        ]);

        $endpoint = '/profiles/'.$user->name.'/notifications';
        $response = $this->get($endpoint, ['Accept' =>  'application/json'])->response;
        $response = json_decode($response->getContent());

        $this->assertCount(1, $response);
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read() {
        $thread = create('App\Thread');
        $user = create('App\User');
        $this->signIn($user);  // need to be a new user

        $thread->subscribe();
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply here',
        ]);
        $notification = $user->fresh()->notifications->first();

        $this->delete('/profiles/'.$user->name.'/notifications/'.$notification->id);

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
