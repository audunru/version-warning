<?php

namespace audunru\VersionWarning\Middleware;

use audunru\VersionWarning\Contracts\VersionServiceContract;
use audunru\VersionWarning\Exceptions\VersionWarningException;
use Closure;
use Illuminate\Support\Facades\Log;

class VersionWarning
{
    /**
     * @SuppressWarnings("unused")
     */
    public function __construct(private VersionServiceContract $versionService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $appVersion = '';

        try {
            $appVersion = $this->versionService->getAppVersion();
            $response->header(config('version-warning.headers.app-version'), $appVersion);
        } catch (VersionWarningException $e) {
            Log::error('Could not determine app version', ['exception' => $e]);
        }

        $clientVersion = $this->versionService->getClientVersion($request);
        if (! empty($clientVersion) && ! empty($appVersion)) {
            $versionWarning = version_compare($clientVersion, $appVersion, '!=');
            if ($versionWarning) {
                $response->header(config('version-warning.headers.version-warning'), $versionWarning);
            }
        }

        return $response;
    }
}
