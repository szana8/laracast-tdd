<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class YouWereMentioned extends Notification
{
    use Queueable;

    /**
     * The new reply.
     *
     * @var
     */
    protected $reply;

    /**
     * Create a new notification instance.
     *
     * @param $reply
     */
    public function __construct($reply)
    {
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
            'message' => $this->reply->owner->name . ' mentioned you in ' . $this->reply->issue->summary,
            'link' => $this->reply->path(),
        ];
    }
}
