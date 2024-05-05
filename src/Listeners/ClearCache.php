<?php

namespace audunru\VersionWarning\Listeners;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearCache
{
    public function handle(): void
    {
        Cache::forget(config('version-warning.cache.key'));
        Log::info('Cleared version-warning cache');
    }
}
