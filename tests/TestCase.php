<?php

namespace Rslanzi\NovaTranslatable\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.locale', 'it');
        $app['config']->set('translatable.locales', ['it', 'en']);
    }
}
