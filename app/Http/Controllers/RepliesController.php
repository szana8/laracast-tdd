<?php

namespace App\Http\Controllers;

use App\Issue;


class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Issue $issue)
    {
        $issue->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return back();
    }
}
