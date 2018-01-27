<?php

namespace App\Listeners;

use App\Events\NewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
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
     * @param  NewReply  $event
     * @return void
     */
    public function handle(NewReply $event)
    {
        $pattern = '#<a href=(\'|")\/profiles\/(.+)\1>@\2<\/a>#';
        preg_match_all($pattern, $event->reply->body, $matches);

        $usernames = isset($matches[2][0]) ? $matches[2] : [];
        if (empty($usernames)) return;

        $users = \App\User::whereIn('name', $usernames)->orderBy('id', 'desc')->get();
        $notified = [];

        foreach ($users as $user) {
            if (isset($notified[$user['name']])) continue;

            $user->notify(new \App\Notifications\YouWereMentioned($event->reply));
            $notified[$user['name']] = 1;
        }
    }
}
