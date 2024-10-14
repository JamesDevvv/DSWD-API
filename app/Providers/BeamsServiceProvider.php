<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pusher\PushNotifications\PushNotifications;

class BeamsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PushNotifications::class, function ($app) {
            return new PushNotifications([
                'instanceId' => env('PUSHER_BEAMS_INSTANCE_ID'),
                'secretKey' => env('PUSHER_BEAMS_SECRET_KEY'),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
