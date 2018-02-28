<?php

namespace App;


trait RecordsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::created(function ($model) use ($event) {
                $model->recordActivity('created');
            });
        }

        static::deleting(function ($model){
            $model->activity()->delete();
        });
    }

    /**
     * Fetch all the model events that require activity recording.
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * Record new activity for the model.
     *
     * @param $event
     * @throws \ReflectionException
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    /**
     * Fetch the activity relationship.
     *
     * @return mixed
     */
    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * Determine the activity type.
     *
     * @param $event
     * @return string
     * @throws \ReflectionException
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}