<?php

namespace App\Providers;

trait Favoritable
{
    public static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favoritable');
    }

    public function favorite($user_id)
    {
        // a user can like a reply only ONE TIME 
        $attributes = ['user_id' => $user_id];
        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function getIsFavoritedAttribute()
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }

    public function unfavorite()
    {
        $this->favorites()->where('user_id', auth()->id())->get()->each->delete();
    }
}