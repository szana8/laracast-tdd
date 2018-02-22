<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * Get the route key name for Laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Fetch all issues that are created by the user.
     *
     * @return string
     */
    public function issues()
    {
        return $this->hasMany(Issue::class)->latest();
    }

    /**
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * @param $issue
     * @return string
     */
    public function visitedIssueCacheKey($issue)
    {
        return sprintf("users.%s.visits.%s", $this->id, $issue->id);
    }

    /**
     * @param $issue
     * @throws \Exception
     */
    public function read($issue)
    {
        cache()->forever(
            $this->visitedIssueCacheKey($issue),
            \Carbon\Carbon::now()
        );
    }
}
