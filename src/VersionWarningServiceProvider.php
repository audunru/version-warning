<?php

namespace audunru\VersionWarning;

use audunru\VersionWarning\Commands\ClearCache;
use audunru\VersionWarning\Contracts\VersionServiceContract;
use audunru\VersionWarning\Listeners\ClearCache as ClearCacheListener;
use audunru\VersionWarning\Services\CachingVersionService;
use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class VersionWarningServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('version-warning')
            ->hasConfigFile()
            ->hasCommand(ClearCache::class);
    }

    /**
     * Register any package services.
     */
    public function packageRegistered()
    {
        $implementation = config('version-warning.cache.enabled')
            ? CachingVersionService::class
            : config('version-warning.services.version');

        $this->app->bind(
            VersionServiceContract::class,
            $implementation
        );

        $this->app->when(CachingVersionService::class)
            ->needs(VersionServiceContract::class)
            ->give(config('version-warning.services.version'));
    }

    public function bootingPackage()
    {
        $events = config('version-warning.cache.clear_on_events', []);
        Event::listen($events, ClearCacheListener::class);
    }
}
