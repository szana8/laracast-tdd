<?php

namespace App\Http\Controllers;

use App\Category;
use App\Filters\IssueFilters;
use App\Issue;
use App\Rules\SpamFree;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category, IssueFilters $filters)
    {
        $issues = $this->getIssues($category, $filters);

        if (request()->wantsJson()) {
            return $issues;
        }

        return view('issue.index', compact('issues'));
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
            'summary' => ['required', new SpamFree()],
            'description' => ['required', new SpamFree()],
            'category_id' => 'required|exists:categories,id'
        ]);

        $issue = Issue::create([
            'user_id' => auth()->id(),
            'category_id' => request('category_id'),
            'summary' => request('summary'),
            'description' => request('description')
        ]);

        return redirect($issue->path())->with('flash', 'Your issue published!');
    }

    /**
     * Display the specified resource.
     *
     * @param $category
     * @param  \App\Issue $issue
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show($category, Issue $issue)
    {
        if (auth()->check()) {
            auth()->user()->read($issue);
        }

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
