<?php

namespace App\Providers;

use App\Libs\CityApi\Helpers\ProviderDynamicSettings;
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

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ProviderDynamicSettings::loadSingletons($this->app);
        ProviderDynamicSettings::loadBinds($this->app);
    }
}
