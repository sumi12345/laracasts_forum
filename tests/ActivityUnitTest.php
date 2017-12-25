<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivityUnitTest extends TestCase
{
    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $thread = create('App\Thread');

        $this->seeInDatabase('activities', [
            'type' => 'created_thread',
            'user_id' => $thread->user_id,
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = \App\Activity::orderBy('id', 'desc')->first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_recoreds_activity_when_a_reply_is_created()
    {
        $reply = create('App\Reply');
        $activity = \App\Activity::orderBy('id', 'desc')->first();

        $this->assertEquals($activity->subject->id, $reply->id);
    }
}
