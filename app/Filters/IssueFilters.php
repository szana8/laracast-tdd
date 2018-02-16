<?php

namespace App\Filters;

use App\User;


class IssueFilters extends Filters
{
    /**
     * @var array
     */
    protected $filters = ['by', 'popular'];

    /**
     * Filter the query by the given username.
     *
     * @param $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * @return mixed
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

}