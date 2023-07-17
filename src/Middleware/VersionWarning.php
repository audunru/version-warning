<?php

namespace audunru\VersionWarning\Middleware;

use audunru\VersionWarning\Contracts\VersionServiceContract;
use audunru\VersionWarning\Exceptions\VersionWarningException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VersionWarning
{
    public function __construct(private VersionServiceContract $versionService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $response = $next($request);

        $appVersion = '';

        try {
            $appVersion = $this->versionService->getAppVersion();
            $response->header(config('version-warning.headers.app-version'), $appVersion);
        } catch (VersionWarningException $e) {
            if (config('version-warning.errors.throw')) {
                throw $e;
            }

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
