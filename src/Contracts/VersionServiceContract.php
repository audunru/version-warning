<?php

namespace audunru\VersionWarning\Contracts;

use Illuminate\Http\Request;

interface VersionServiceContract
{
    /**
     * Get app version running on the server.
     */
    public function getAppVersion(): string;

    /**
     * Get app version running on the client.
     */
    public function getClientVersion(Request $request): string;
}
