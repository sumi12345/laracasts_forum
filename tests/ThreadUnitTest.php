<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
}