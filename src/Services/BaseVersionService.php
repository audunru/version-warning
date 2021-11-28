<?php

namespace audunru\VersionWarning\Services;

abstract class BaseVersionService
{
    /**
     * Get the name of the HTTP header containing the client version.
     */
    protected function getClientVersionHeaderName(): string
    {
        return config('version-warning.headers.app-version');
    }
}
