<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;

class ThreadUnitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel() {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /** @test */
    public function a_thread_can_have_a_string_path()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->id}",
            $this->thread->path()
        );
    }

    /** @test */
    public function a_thread_can_be_subscribed_to() {
        $subscription = $this->thread->subscribe($userId = 1);

        $this->assertEquals(1, $subscription->user_id);
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from() {
        $subscription = $this->thread->subscribe($userId = 1);

        $this->thread->unsubscribe($userId);

        $this->notSeeInDatabase('thread_subscriptions', ['id' => $subscription->id]);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it() {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribedTo);

        $this->thread->subscribe();

        $this->assertTrue($this->thread->fresh()->isSubscribedTo);
    }

    /** @test */
    public function a_thread_notifies_all_subscribers_when_a_reply_is_added()
    {
        // https://laravel.com/docs/5.1/testing#mocking-facades
        Notification::shouldReceive('send')->once();

        $this->signIn();
        $this->thread->subscribe();

        $this->thread->addReply([
            'user_id' => create('App\User')->id, // not the signed in one
            'body' => 'a_thread_notifies_all_subscribers_when_a_reply_is_added'
        ]);
    }

    /** @test */
    public function a_thread_may_be_locked()
    {
        $this->assertFalse($this->thread->locked);
    }
}
