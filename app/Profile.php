<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Profile extends Model
{
    protected $guarded = [];
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

}
