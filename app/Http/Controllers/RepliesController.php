<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreatePostRequest;
use App\Issue;
use App\Reply;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Gate;


class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * @param $chanelId
     * @param Issue $issue
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
     * @param CreatePostRequest $form
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($categoryId, Issue $issue, CreatePostRequest $form)
    {
        return $issue->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), ['body' => 'required']);

            $reply->update([
                'body' => request('body')
            ]);
        } catch (\Exception $e) {
            return response('Sorry your reply could not be updated at this time', 422);
        }
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
