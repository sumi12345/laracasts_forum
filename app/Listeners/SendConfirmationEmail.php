<?php

namespace App\Listeners;

use App\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConfirmationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        \Mail::send(
            'emails.confirm-email',
            ['user' => $user],
            function ($m) use ($user) {
                $m->from('shumei_chen@qq.com', 'Laracasts Forum');
                $m->to($user->email, $user->name);
                $m->subject('Please confirm your email!');
            });
    }
}
