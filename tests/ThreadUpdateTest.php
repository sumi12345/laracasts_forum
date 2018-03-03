<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadUpdateTest extends TestCase
{
    /** @test */
    public function unauthorized_users_may_not_update_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->patch($thread->path(), []);

        $this->seeStatusCode(403);
    }

    /** @test */
    public function a_thread_requires_title_and_body_to_be_updated()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed'
        ]);
        $this->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed body'
        ]);
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_can_be_updated()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->json('patch', $thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body.'
        ]);
        echo $this->response->getContent();

        $this->assertEquals('Changed', $thread->fresh()->title);
    }
}
