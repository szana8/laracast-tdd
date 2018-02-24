<?php

namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $issue;

    /**
     * Visits constructor.
     * @param $issue
     */
    public function __construct($issue)
    {
        $this->issue = $issue;
    }

    /**
     *
     */
    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    /**
     * @return int
     */
    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }


    /**
     * @return $this
     */
    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    /**
     * @return string
     */
    public function cacheKey()
    {
        return app()->environment('testing') ? "testing_issues.{$this->issue->id}.visits" :  "issues.{$this->issue->id}.visits";
    }
}