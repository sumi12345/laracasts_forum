<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AvatarAddTest extends TestCase
{
    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->post('/api/users/1/avatar', [], ['X-Requested-With' => 'XMLHttpRequest']);

        $this->seeStatusCode(401); // middleware/authenticate Unauthorized
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn();

        $this->post('/api/users/'.auth()->id().'/avatar', [
            'avatar' => 'not-an-image'
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $this->seeStatusCode(422);  // {"avatar":["The avatar must be an image."]}
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        $headers = ['CONTENT_TYPE' => 'application/json', 'Accept' => 'application/json'];
        $file = getImage();
        $file_hash = sha1_file($file);
        $this->requestWithUpload('post', '/api/users/'. auth()->id() .'/avatar', $headers, [
            'avatar' => $file
        ]);

        $this->seeStatusCode(200);
        $this->assertTrue(file_exists('storage/avatars/'.$file_hash.'.jpg'));
        $this->assertEquals('storage/avatars/'.$file_hash.'.jpg', auth()->user()->avatar_path);
    }
}
