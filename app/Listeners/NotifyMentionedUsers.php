<?php

namespace App\Listeners;

use App\User;
use App\Events\IssueReceivedNewReply;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  IssueReceivedNewReply $event
     * @return void
     */
    public function handle(IssueReceivedNewReply $event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
