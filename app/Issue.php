<?php

namespace App;

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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
           $builder->withCount('replies');
        });

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
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
        return $this->replies()->create($reply);
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
}
