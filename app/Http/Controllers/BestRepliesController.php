<?php

namespace App\Http\Controllers;

use App\Reply;

class BestRepliesController extends Controller
{
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->issue);

        $reply->issue->markBestReply($reply);

        if (request()->wantsJson()) {
            return response('', 201);
        }
    }
}
