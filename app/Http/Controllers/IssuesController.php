<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Category;
use App\Trending;
use App\Rules\SpamFree;
use Illuminate\Http\Request;
use App\Filters\IssueFilters;
use Illuminate\Support\Facades\Redis;

class IssuesController extends Controller
{
    /**
     * Create new IssueController instance.
     */
    public function __construct()
    {
        $this->middleware(['web','auth'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Category $category
     * @param IssueFilters $filters
     * @param Trending $trending
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category, IssueFilters $filters, Trending $trending)
    {
        $issues = $this->getIssues($category, $filters);

        if (request()->wantsJson()) {
            return $issues;
        }

        return view('issue.index', [
            'issues' => $issues,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('issue.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', new SpamFree()],
            'description' => ['required', new SpamFree()],
            'category_id' => 'required|exists:categories,id'
        ]);

        $issue = Issue::create([
            'user_id' => auth()->id(),
            'category_id' => request('category_id'),
            'title' => request('title'),
            'description' => request('description'),
            'slug' => request('title')
        ]);

        return redirect($issue->path())->with('flash', 'Your issue published!');
    }

    /**
     * Display the specified resource.
     *
     * @param $category
     * @param  \App\Issue $issue
     * @param Trending $trending
     * @return \Illuminate\Http\Response
     */
    public function show($category, Issue $issue, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($issue);
        }

        $trending->push($issue);

        $issue->increment('visits');

        return view('issue.show',compact('issue'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $category
     * @param  \App\Issue $issue
     * @return void
     * @throws \Exception
     */
    public function destroy($category, Issue $issue)
    {
        $this->authorize('update', $issue);

        $issue->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/issues');
    }

    /**
     * @param Category $category
     * @param IssueFilters $filters
     * @return mixed
     */
    public function getIssues(Category $category, IssueFilters $filters)
    {
        $issues = Issue::latest()->filter($filters);

        if ($category->exists) {
            $issues->where('category_id', $category->id);
        }

        return $issues->paginate(25);
    }
}
