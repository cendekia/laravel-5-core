<?php

namespace App\Providers;

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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['admin'] = $this->app->share(function($app) {
            return new \App\Helpers\Admin();
        });

        $this->app['attr'] = $this->app->share(function($app) {
            return new \App\Helpers\Attr();
        });

        $this->app['site'] = $this->app->share(function($app) {
            return new \App\Helpers\Site();
        });
    }
}
