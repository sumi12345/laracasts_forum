<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfilesTest extends TestCase
{
    /** @test */
    public function a_user_has_a_profile()
    {
        $user = create('App\User');

        $this->get(url('/profiles', $user->name))
            ->see($user->name);
    }

    /** @test */
    public function profiles_display_all_threads_created_by_the_user()
    {
        $user = create('App\User');

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->get(url('/profiles', $user->name))
            ->see($thread->title);
    }
}
