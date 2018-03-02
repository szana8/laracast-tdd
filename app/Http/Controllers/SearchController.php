<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
        if (\request()->expectsJson()) {
            return Issue::search(request('q'))->paginate(25);;
        }

        return view('issue.search', [
            'trending' => $trending->get()
        ]);
    }
}
