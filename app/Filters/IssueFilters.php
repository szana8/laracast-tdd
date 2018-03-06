<?php

namespace App\Filters;

use App\User;

class IssueFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by the given username.
     *
     * @param $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query according the most popular issues.
     *
     * @return mixed
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Filter the query according the most unanswered issues.
     *
     * @return mixed
     */
    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}
