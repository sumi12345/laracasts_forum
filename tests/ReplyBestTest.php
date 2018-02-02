<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyBestTest extends TestCase
{
    /** @test */
    public function only_the_thread_creator_may_mark_reply_as_best()
    {
        $thread = create('App\Thread');
        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);
        $this->signIn();

        $this->json('post', route('reply.best', [$replies[1]->id]))
            ->seeStatusCode(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function thread_creator_may_mark_any_reply_as_best()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

        $this->json('post', route('reply.best', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }
}
