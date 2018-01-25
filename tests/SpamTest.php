<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Spam;

class SpamTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->disableExceptionHandling();
    }

    /** @test */
    public function it_checks_for_keywords()
    {
        $spam = new \App\Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->setExpectedException(\Exception::class);

        $spam->detect('Yahoo Customer Support');
    }

    /** @test */
    public function it_checks_for_any_key_being_held_down()
    {
        $spam = new Spam();

        $this->setExpectedException(\Exception::class);

        $spam->detect('Hello world aaaaaaaaaaaaa');
    }
}
