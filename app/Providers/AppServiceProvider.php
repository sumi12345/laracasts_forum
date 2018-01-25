<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // share 会在 database migration 之前执行
        //view()->share('channels', \App\Channel::all());

        view()->composer('*', function ($view) {
            $channels = \Cache::rememberForever('channels', function() {
                return Channel::all();
            });

            $view->with('channels', $channels);
        });

        // $attribute: name of the input
        \Validator::extend('spamfree', function($attribute, $value, $parameters, $validator) {
            try {
                return ! app(\App\Spam::class)->detect($value);
            } catch(\Exception $e) {
                return false;
            }
        });
    }

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
}
