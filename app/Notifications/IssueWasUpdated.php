<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IssueWasUpdated extends Notification
{
    use Queueable;

    /**
     * The issue that was updated.
     *
     * @var
     */
    protected $issue;

    /**
     * The new reply.
     *
     * @var
     */
    protected $reply;

    /**
     * Create a new notification instance.
     *
     * @param $issue
     * @param $reply
     */
    public function __construct($issue, $reply)
    {
        $this->issue = $issue;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'message' => $this->reply->owner->name . ' replied to ' . $this->issue->summary,
            'link' => $this->reply->path(),
        ];
    }
}
