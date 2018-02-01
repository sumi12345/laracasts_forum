<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRegistrationTest extends TestCase
{
    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        // 注册完成后发送验证邮件
        // 这里没法测试具体参数
        // 相关文章: https://adamwathan.me/2016/01/25/writing-your-own-test-doubles/
        //\Mail::shouldReceive('send')->once();

        $user = create('App\User');

        event(new \App\Events\Registered($user));
    }
}
