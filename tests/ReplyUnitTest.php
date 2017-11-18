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
}
