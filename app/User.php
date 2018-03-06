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
        'name', 'email', 'password', 'avatar_path',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean'
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
     * Fetch the last published reply for the user.
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * Get all activity for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the cache key for when a user reads a issue.
     *
     * @param Issue $issue
     * @return string
     */
    public function visitedIssueCacheKey($issue)
    {
        return sprintf('users.%s.visits.%s', $this->id, $issue->id);
    }

    /**
     * Record that the user has read the given issue.
     *
     * @param Issue $issue
     * @throws \Exception
     */
    public function read($issue)
    {
        cache()->forever(
            $this->visitedIssueCacheKey($issue),
            \Carbon\Carbon::now()
        );
    }

    /**
     * Get the path to the user's avatar.
     *
     * @param $avatar
     * @return string
     */
    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ? 'storage/'.$avatar : 'images/avatars/default.png');
    }

    /**
     * Mark the user's account as confirmed.
     */
    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;

        $this->save();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['JohnDoe']);
    }
}
