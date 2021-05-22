<?php

namespace Rslanzi\NovaTranslatable\Tests;

use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Rslanzi\NovaTranslatable\NovaTranslatableServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Route::middlewareGroup('nova', []);

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            NovaTranslatableServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.locale', 'it');
        $app['config']->set('translatable.locales', ['it', 'en']);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->artisan('migrate:fresh');

        include_once __DIR__.'/database/migrations/create_stub_tables.php';

        (new \CreateTables())->up();
    }
}
