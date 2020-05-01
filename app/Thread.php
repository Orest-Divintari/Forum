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
        return $this->replies()->create($reply);
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

    public function subscribe($userId)
    {
        $this->subscribers()->attach(['user_id' => $userId]);
    }

    public function subscribers()
    {
        return $this->belongsToMany('App\User', 'subscriptions');
    }
}