<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Reply;
use App\Http\Requests\CreatePostRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RepliesController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Fetch all relevant replies.
     *
     * @param $chanelId
     * @param Issue $issue
     * @return LengthAwarePaginator
     */
    public function index($chanelId, Issue $issue)
    {
        return $issue->replies()->paginate(20);
    }

    /**
     * Persist a new reply.
     *
     * @param $categoryId
     * @param Issue $issue
     * @param CreatePostRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($categoryId, Issue $issue, CreatePostRequest $request)
    {
        if ($issue->locked) {
            return response('Issue is locked.', 422);
        }

        return $issue->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * Update an existing reply.
     *
     * @param Reply $reply
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), ['body' => 'required']);

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
