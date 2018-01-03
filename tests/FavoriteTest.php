<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FavoriteTest extends TestCase
{
    /** @test */
    public function guests_can_not_favorite_anything()
    {
        // 先有一个 reply, 否则 404
        create('App\Reply');

        $this->post('replies/1/favorites')
             ->assertRedirectedTo('/auth/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('replies/'.$reply->id.'/favorites')
            ->assertCount(1, $reply->favorites);

        $this->delete('replies/'.$reply->id.'/favorites')
            ->assertCount(0, $reply->fresh()->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('replies/'.$reply->id.'/favorites');
        $this->post('replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->favorites);

    }
}
