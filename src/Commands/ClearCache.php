<?php

namespace audunru\VersionWarning\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version-warning:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear version-warning cache';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Cache::forget(config('version-warning.cache.key'));
        $this->info('Cleared version-warning cache');

        return 0;
    }
}
