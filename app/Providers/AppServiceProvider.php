<?php

namespace App\Providers;

use App\Channel;
use App\Observers\ReplyObserver;
use App\Observers\ThreadObserver;
use App\Reply;
use App\Thread;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Thread::observe(ThreadObserver::class);
        Reply::observe(ReplyObserver::class);
        // \View::composer(['threads.create', 'layouts.app'], function($view){
        //     $view->with('channels', \App\Channel::all());
        // });
        View::composer('*', function ($view) {

            $channels = Cache::rememberForever('channels', function () {
                // if it is not in the cache then retrieve all channels

                return Channel::all();
            });
            $view->with('channels', $channels);
        });

        //  \View::share('channels', \App\Channel::all());

    }
}