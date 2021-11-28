<?php

return [
    'services' => [
        /*
         * Class responsible for comparing client and app version.
         */
        'version' => \audunru\VersionWarning\Services\NodeVersionService::class,
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
        'enabled' => true,
        /*
        * Cache key used to store current app version.
        */
        'key'     => 'app-version',
    ],
];
