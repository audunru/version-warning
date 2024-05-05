<?php

namespace audunru\VersionWarning\Listeners;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearCache
{
    public function handle($event = null): void
    {
        if ($event instanceof CommandFinished && ! in_array($event->command, config('version-warning.cache.clear_after_commands'))) {
            return;
        }
        Cache::forget(config('version-warning.cache.key'));
        Log::info('Cleared version-warning cache');
    }
}
