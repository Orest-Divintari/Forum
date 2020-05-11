<?php

namespace App;

use App\Channel;
use App\Events\ThreadHasNewReply;
use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Thread extends Model
{
    use RecordsActivity, RecordsVisit;
    protected $touches = ['activity'];
    protected $guarded = [];
    protected $with = ['channel', 'creator'];
    protected $recordableEvents = ['created'];
    protected $appends = ['isSubscribedTo'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($thread) {
            $thread->slug = Str::slug($thread->title);
        });
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function addReply($reply)
    {

        $reply = $this->replies()->create($reply);
        // notify the subscribers for the replies that are created by the other users ( not for their own replies)
        // if i add a reply, i don't need a notification
        // i need a notification for the replies that are added by the other users to the thread.
        // this returns a collection of subscription ( subscriber ) instances
        // for each of them we call notify

        event(new ThreadHasNewReply($this, $reply));

        // //prepare notifications for all subscribers for this thread
        // foreach ($this->subscriptions as $subscription) {
        //     if ($subscription->user_id != $reply->user_id) {
        //         $subscription->user->notify(new ThreadWasUpdated($this, $reply));
        //     }
        // }
        return $reply;
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?? auth()->id(),
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where([
            'user_id' => $userId ?? auth()->id(),
        ])->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor()
    {
        $key = auth()->user()->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }

    public function setSlugAttribute($slug)
    {
        if (Thread::whereSlug($slug = Str::slug($slug))->exists()) {

            $slug = $this->incrementSlug($slug);
        }
        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug, $count = 2)
    {
        $originalSlug = $slug;

        while (Thread::whereSlug($slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        return $slug;
        // $previousSlug = Thread::whereTitle($this->title)->latest('id')->pluck('slug')->first();

        // $previousSlugCount = Str::of($previousSlug)->explode('-')->last();
        // $previousSlug = Str::of($previousSlug)->explode('-')->takeWhile(function ($value) {
        //     return !is_numeric($value);
        // });
        // $previousSlug = $previousSlug->implode('-');
        // if (is_numeric($previousSlugCount)) {

        //     return $slug = $previousSlug . '-' . ($previousSlugCount + 1);
        // }
        // return $slug . '-2';
    }

    public function markBestReply($reply)
    {
        $reply->thread->update(['best_reply_id' => $reply->id]);
    }
}