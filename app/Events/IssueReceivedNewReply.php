<?php

namespace App\Events;


use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class IssueReceivedNewReply
{
    use Dispatchable, SerializesModels;

    public $reply;

    /**
     * IssueReceivedNewReply constructor.
     * @param $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

}
