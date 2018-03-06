<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * Fetch all trending issues.
     *
     * @return array
     */
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    /**
     * Push a new issue to the trending list.
     *
     * @param $issue
     */
    public function push($issue)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $issue->title,
            'path' => $issue->path()
        ]));
    }

    /**
     * Get the cache key name.
     *
     * @return string
     */
    public function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_issues' : 'trending_issues';
    }

    /**
     * Reset all trending issues.
     */
    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}
