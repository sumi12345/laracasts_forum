<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRegistrationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        \Mail::swap(Mockery::spy('Illuminate\Contracts\Mail\Mailer'));
    }

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        // 注册完成后发送验证邮件
        // 这里没法测试具体参数
        // 相关文章: https://adamwathan.me/2016/01/25/writing-your-own-test-doubles/
        \Mail::shouldReceive('send')->once();

        $user = create('App\User');

        event(new \App\Events\Registered($user));
    }

    /** @test */
    public function user_can_confirm_their_email()
    {
        $user = make('App\User');

        $this->call('post', '/auth/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password
        ]);

        $this->assertRedirectedTo('/threads');

        // 注册成功, confirmed 为 false, confirmation_token 为随机字符串
        $user = \App\User::orderBy('id', 'desc')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotEquals('', $user->confirmation_token);

        // 访问确认邮件链接, confirmed 为 true, confirmation_token 为空
        $this->get('/auth/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);
        $this->assertSame('', $user->fresh()->confirmation_token);
    }
}
