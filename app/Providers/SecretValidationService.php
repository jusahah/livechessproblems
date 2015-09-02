<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SecretValidationService extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      $this->app['validator']->extend('checkSecret', function ($attribute, $value, $parameters) {
        $c = \App\Collection::where('secret', $value)->first();
        if ($c) return true;
        return false;
      });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
