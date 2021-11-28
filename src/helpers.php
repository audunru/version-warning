<?php

use audunru\VersionWarning\Contracts\VersionServiceContract;

if (! function_exists('app_version')) {
    /**
     * Get app version running on the server.
     */
    function app_version()
    {
        return app(VersionServiceContract::class)->getAppVersion();
    }
}
