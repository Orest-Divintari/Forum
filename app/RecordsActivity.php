<?php

namespace App;

trait RecordsActivity
{


    public static function bootRecordsActivity()
    {


        $recordableEvents = self::recordableEvents();

        foreach ($recordableEvents as $event) {

            static::$event(function ($model) use ($event) {

                $activityType = $model->getActivityType($event);
                $model->recordActivity($activityType);
            });
        }


        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    public function recordActivity($type)
    {
        $this->activity()->create([
            'type' => $type,
            'user_id' => $this->creator->id
        ]);
    }


    public function getActivityType($event)
    {
        $type = strtolower(class_basename($this));
        return "{$event}_{$type}";
    }

    public static function recordableEvents()
    {
        if (isset(static::$recordableEvents)) {
            $recordableEvents =  static::$recordableEvents;
        }
        $recordableEvents = ['created'];
        return $recordableEvents;
    }
}