<?php

namespace Acadea\FullSite;

use Acadea\FullSite\Facades\FullSite;
use Illuminate\Support\ServiceProvider;

class FullSiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/fullsite-search.php' => config_path('fullsite-search.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/fullsite-search.php', 'fullsite-search');

        if(!config('fullsite-search.api.disabled')){
            FullSite::routes();
        }

    }
}
