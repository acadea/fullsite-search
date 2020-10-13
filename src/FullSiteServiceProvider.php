<?php

namespace Acadea\FullSite;

use Illuminate\Support\ServiceProvider;
use Acadea\FullSite\Facades\Commands\FullSiteCommand;

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
    }


}
