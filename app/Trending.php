<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * @return array
     */
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    /**
     * @param $issue
     */
    public function push($issue)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'summary' => $issue->summary,
            'path' => $issue->path()
        ]));
    }

    /**
     * @return string
     */
    public function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_issues' :  'trending_issues';
    }

    /**
     *
     */
    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}