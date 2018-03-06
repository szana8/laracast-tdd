<?php

namespace App\Http\Controllers;

use App\Issue;

class LockedIssuesController extends Controller
{
    /**
     * @param Issue $issue
     * @return bool
     */
    public function store(Issue $issue)
    {
        $issue->update(['locked' => true]);
    }

    /**
     * @param Issue $issue
     * @return bool
     */
    public function destroy(Issue $issue)
    {
        $issue->update(['locked' => false]);
    }
}
