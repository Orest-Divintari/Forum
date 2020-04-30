<?php

namespace App;

use http\Env;
use http\Exception;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];
    protected $touch;
    public function subject()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function feed($user, $take=50)
    {
        //Activity:: === static::
        return static::where('user_id', $user->id)
                    ->latest()
                    ->with('subject')
                    ->take($take)
                    ->get()
                    ->groupBy(function($activity){
                        return $activity->created_at
                                        ->format('Y-m-d');
                    });

    }

}



