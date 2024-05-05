<?php

return [
    'services' => [
        /*
         * Class responsible for comparing client and app version.
         */
        'version' => audunru\VersionWarning\Services\NodeVersionService::class,
    ],

    'headers' => [
        /*
        * HTTP header name containing app version.
        */
        'app-version'     => 'X-App-Version',
        /*
         * HTTP header name indicating that client and server are not using the same version.
         */
        'version-warning' => 'X-Version-Warning',
    ],
    'cache' => [
        /*
        * Enable or disable cache.
        */
        'enabled' => env('VERSION_WARNING_CACHE_ENABLED', true),
        /*
        * Cache key used to store current app version.
        */
        'key'     => 'app-version',
        /*
        * Automatically clear the cache on these events.
        */
        'clear_on_events'     => [],
    ],
    'errors' => [
        /*
        * Log or throw errors.
        * If set to false, errors will be logged (not thrown).
        * If set to true, errors will be thrown (not logged).
        * If you choose to throw errors, you should handle them in Laravel's Handler.php.
        */
        'throw' => false,
    ],
];
