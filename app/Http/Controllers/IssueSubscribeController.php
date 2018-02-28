<?php

namespace App\Http\Controllers;

use App\Issue;

class IssueSubscribeController extends Controller
{
    /**
     * Store a new issue subscription.
     *
     * @param $categoryId
     * @param Issue $issue
     */
    public function store($categoryId, Issue $issue)
    {
        $issue->subscribe();
    }

    /**
     * Delete an existing issue subscription.
     *
     * @param $categoryId
     * @param Issue $issue
     */
    public function destroy($categoryId, Issue $issue)
    {
        $issue->unSubscribe();
    }
}
