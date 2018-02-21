<?php

namespace App\Http\Controllers;

use App\Issue;
use Illuminate\Http\Request;

class IssueSubscribeController extends Controller
{
    /**
     * @param $categoryId
     * @param Issue $issue
     */
    public function store($categoryId, Issue $issue)
    {
        $issue->subscribe();
    }

    /**
     * @param $categoryId
     * @param Issue $issue
     */
    public function destroy($categoryId, Issue $issue)
    {
        $issue->unSubscribe();
    }
}
