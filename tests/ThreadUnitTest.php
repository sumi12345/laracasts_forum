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

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        // 向帖子添加一条回复
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        // 帖子应该有一条回复
        $this->assertCount(1, $this->thread->replies);
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
    public function a_thread_belongs_to_a_channel() {
        $thread = make('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function a_thread_can_have_a_string_path()
    {
        $thread = create('App\Thread');
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }

    /** @test */
    public function a_thread_can_be_subscribed_to() {
        $thread = create('App\Thread');

        $subscription = $thread->subscribe($userId = 1);

        $this->assertEquals(1, $subscription->user_id);
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from() {
        $thread = create('App\Thread');
        $subscription = $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->notSeeInDatabase('thread_subscriptions', ['id' => $subscription->id]);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it() {
        $thread = create('App\Thread');
        $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->fresh()->isSubscribedTo);
    }
}
