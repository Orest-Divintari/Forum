<?php

namespace App\Observers;

use App\Favorite;

class FavoriteObserver
{
    /**
     * Handle the favorite "created" event.
     *
     * @param  \App\Favorite  $favorite
     * @return void
     */
    public function created(Favorite $favorite)
    {
        $favorite->activity()->create([
            'description' => 'liked',

        ]);
    }

    /**
     * Handle the favorite "updated" event.
     *
     * @param  \App\Favorite  $favorite
     * @return void
     */
    public function updated(Favorite $favorite)
    {
        //
    }

    /**
     * Handle the favorite "deleted" event.
     *
     * @param  \App\Favorite  $favorite
     * @return void
     */
    public function deleted(Favorite $favorite)
    {
        //
    }

    /**
     * Handle the favorite "restored" event.
     *
     * @param  \App\Favorite  $favorite
     * @return void
     */
    public function restored(Favorite $favorite)
    {
        //
    }

    /**
     * Handle the favorite "force deleted" event.
     *
     * @param  \App\Favorite  $favorite
     * @return void
     */
    public function forceDeleted(Favorite $favorite)
    {
        //
    }
}
