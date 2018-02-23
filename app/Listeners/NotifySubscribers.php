<?php

namespace App\Listeners;

use App\Events\IssueReceivedNewReply;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  IssueReceivedNewReply  $event
     * @return void
     */
    public function handle(IssueReceivedNewReply $event)
    {
        $event->reply->issue->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
