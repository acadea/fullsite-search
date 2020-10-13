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

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/fullsite-search'),
            ], 'views');

            $migrationFileName = 'create_fullsite_search_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                FullSiteCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'fullsite-search');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/fullsite-search.php', 'fullsite-search');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
