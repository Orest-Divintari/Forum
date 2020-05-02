<?php

namespace App;

use App\Channel;
use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;
    protected $touches = ['activity'];
    protected $guarded = [];
    protected $with = ['channel', 'creator'];
    protected $recordableEvents = ['created'];
    protected $appends = ['isSubscribedTo'];

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
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
        $this->subscriptions->filter(function ($subscriber) use ($reply) {
            return $subscriber->user_id != $reply->user_id;
        })->each->notify($reply);
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
}