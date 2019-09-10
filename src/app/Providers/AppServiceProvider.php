<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
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
        //
    }

    public function boot()
    {
        Validator::extend('latitude', function ($attribute, $value, $parameters, $validator) {
            // This will only accept valid Latitude range -90 to 90.
            return preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/', $value);
        });

        Validator::extend('longitude', function ($attribute, $value) {
            // This will only accept valid Longitude -180 to 180.
            return preg_match('/^[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $value);
        });

        // Binding Order repository Interface and eloquent model

        $this->app->bind(
            'App\Repositories\OrderRepositoryInterface',
            'App\Repositories\OrderEloquentRepository'
        );
    }
}
