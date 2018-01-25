<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Notifications\DatabaseNotification;

class NotificationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->signIn(create('App\User'));
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_add_a_reply() {
        $thread = create('App\Thread');

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
        create(DatabaseNotification::class, ['notifiable_id' => auth()->id()]);

        $endpoint = '/profiles/'.auth()->user()->name.'/notifications';
        $response = $this->get($endpoint, ['Accept' =>  'application/json'])->response;

        $this->assertCount(1, json_decode($response->getContent()));
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read() {
        $notification = create(DatabaseNotification::class, ['notifiable_id' => auth()->id()]);
        $user = auth()->user();

        $endpoint = '/profiles/'.$user->name.'/notifications/'.$notification->id;
        $this->delete($endpoint);

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
