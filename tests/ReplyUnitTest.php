<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyUnitTest extends TestCase
{
    /** @test */
    public function it_has_an_owner()
    {
        $reply = factory('App\Reply')->make();
        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create('App\User');
        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = \Carbon\Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_fetchs_mentioned_users_starting_with_given_characters()
    {
        create('App\User', ['name' => 'JohnDoe']);
        create('App\User', ['name' => 'JaneDoe']);

        $this->json('get', '/api/users', ['name' => 'John']);

        $usernames = array_column(json_decode($this->response->getContent()), 'name');
        $this->assertTrue(false !== array_search('JohnDoe', $usernames));
        $this->assertTrue(false === array_search('JaneDoe', $usernames));
    }

    /** @test */
    public function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->isBest());
    }
}
