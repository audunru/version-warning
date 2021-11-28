<?php

namespace audunru\VersionWarning;

use audunru\VersionWarning\Commands\ClearCache;
use audunru\VersionWarning\Contracts\VersionServiceContract;
use audunru\VersionWarning\Services\CachingVersionService;
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
}
