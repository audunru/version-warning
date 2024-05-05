<?php

namespace audunru\VersionWarning\Tests;

use audunru\VersionWarning\Middleware\VersionWarning;
use audunru\VersionWarning\VersionWarningServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @SuppressWarnings("unused")
     */
    protected function getPackageProviders($app)
    {
        return [VersionWarningServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', 'true' === env('APP_DEBUG'));
        $app['config']->set('app.key', substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 5)), 0, 32));
        $app['config']->set('version-warning.cache.clear_on_events', ['some-event']);
        $app->register(VersionWarningServiceProvider::class);
    }

    protected function defineRoutes($router)
    {
        $router->get('/test', function () {
            return [];
        })->middleware([
            VersionWarning::class,
        ])->name('test');
    }

    /**
     * @SuppressWarnings("unused")
     */
    protected function getPackageAliases($app)
    {
        return [];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
