<?php

namespace App;

use App\Events\IssueHasNewReply;
use App\Notifications\IssueWasUpdated;
use Illuminate\Database\Eloquent\Model;


class Issue extends Model
{

    use RecordsActivity;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $with = ['creator', 'category'];

    /**
     * @var array
     */
    protected $appends = ['isSubscribedTo'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope('replyCount', function ($builder) {
//           $builder->withCount('replies');
//        });

        static::deleting(function ($issue) {
            $issue->replies->each->delete();
        });
    }

    /**
     * Get the string path of the issue.
     *
     * @return string
     */
    public function path()
    {
        return '/issues/' . $this->category->slug . '/' . $this->id;
    }

    /**
     * An issue has many replies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    /**
     * An issue belongs to a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * An issue belongs to a category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Add a reply to the issue.
     *
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;
    }

    /**
     * Notify the subscribers for the new reply.
     *
     * @param $reply
     */
    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    /**
     * Apply all relevant issue filters.
     *
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * A user can subscribe to an issue.
     *
     * @param null $userId
     * @return Issue
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    /**
     * A user can unsubscribe from an issue.
     *
     * @param null $userId
     */
    public function unSubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * An issue has many subscribers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(IssueSubscription::class);
    }

    /**
     * Is the authenticated user subscribed to the issue.
     *
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    /**
     * @param $user
     * @return bool
     * @throws \Exception
     */
    public function hasUpdateFor($user)
    {
        return $this->updated_at > cache($user->visitedIssueCacheKey($this));
    }
}
