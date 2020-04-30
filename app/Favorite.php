<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Activity;

class Favorite extends Model
{
    use RecordsActivity;
    protected $guarded = [];


    public function favoritable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function activity()
    {
        return $this->morphOne('App\Activity', 'subject');
    }
}