<?php

namespace App;

use App\Providers\Favoritable;
use App\RecordsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];
    protected $with = ['creator', 'favorites'];
    protected $withCount = ['favorites'];
    protected $recordableEvents = ['created'];
    protected $appends = ['isFavorited'];
    // protected $touches = ['thread'];
    public static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
            app(Reputation::class)->award($reply->creator, 'created_reply');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
            if ($reply->isBest()) {
                $reply->thread->update(['best_reply_id' => null]);
            }
        });

        // The below code can be replaced by withCount in the reply relationship in Thread model
        // static::addGlobalScope('facovoriteCount', function($builder){
        //     $builder->withCount('favorites');
        // });

        // static::addGlobalScope('creator', function($builder){
        //     $builder->with('creator');
        // });

        // static::addGlobalScope('favorites', function($builder){
        //     $builder->with('favorites');
        // });
    }
    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function path()
    {
        return "{$this->thread->path()}#reply-{$this->id}";
    }
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    public function recordsActivity($description)
    {
        // $this->activity()->create([
        //     'user_id' => $this->creator->id
        // ]);
    }

    public function getUserId()
    {
        // return class_basename($this) == 'Reply' ? $this->
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function setBodyAttribute($body)
    {

        $this->attributes['body'] = preg_replace(
            '/@([\w\-]+)/',
            '<a href="/profiles/$1">$0</a>',
            $body
        );
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getBodyAttribute($body)
    {
        return Purify::clean($body);
    }
}