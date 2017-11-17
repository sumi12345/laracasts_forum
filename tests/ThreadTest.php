<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadTest extends TestCase
{
    // 在测试开始时迁移, 结束后销毁
    //use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_threads()
    {
        $thread = factory('App\Thread')->create();
        $this->visit('/threads')->see($thread->title);
    }

    /** @test */
    public function a_user_can_browse_a_single_thread() {
        $thread = factory('App\Thread')->create();
        $this->visit('/threads/'.$thread->id)->see($thread->title);
    }
}
