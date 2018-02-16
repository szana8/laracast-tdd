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
        $this->middleware(['web', 'auth'])->only('store');
    }

    /**
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($categoryId, Issue $issue)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $issue->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return back();
    }
}
