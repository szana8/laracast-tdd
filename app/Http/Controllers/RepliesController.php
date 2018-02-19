<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Reply;


class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index($chanelId, Issue $issue)
    {
        return $issue->replies()->paginate(20);
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

        $reply = $issue->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    /**npm
     * @param Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update([
            'body' => request('body')
        ]);
    }

    /**
     * Delete the reply which belongs to an issue.
     *
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->wantsJson()) {
            return response(['status' => 'Status deleted.']);
        }

        return back();
    }
}
