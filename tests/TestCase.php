<?php

namespace Acadea\FullSite\Tests;

use Acadea\FullSite\FullSiteServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate',
            ['--database' => 'sqlite']
        )->run();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Acadea\\FullSite\\Tests\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    }

    protected function getPackageProviders($app)
    {
        return [
            FullSiteServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);




//        (new \CreatePackageTable())->up();


    }
}
