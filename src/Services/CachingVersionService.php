<?php

namespace audunru\VersionWarning\Services;

use audunru\VersionWarning\Contracts\VersionServiceContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CachingVersionService implements VersionServiceContract
{
    public function __construct(private VersionServiceContract $versionService)
    {
    }

    /**
     * Get app version running on the server.
     */
    public function getAppVersion(): string
    {
        return Cache::rememberForever(config('version-warning.cache.key'), function () {
            return $this->versionService->getAppVersion();
        });
    }

    /**
     * Get app version running on the client.
     */
    public function getClientVersion(Request $request): string
    {
        return $this->versionService->getClientVersion($request);
    }
}
