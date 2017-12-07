<?php

namespace App\Components\Msg;

use Illuminate\Support\ServiceProvider;

class MsgServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('messaging', function ($app) {
            return new MsgManager(app('Illuminate\Contracts\Events\Dispatcher'));
        });
    }
}
