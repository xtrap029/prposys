<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SessionConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        config(['session.lifetime' => \App\Settings::where('type', 'SESSION_LIFETIME')->first()->value]);
    }
}
