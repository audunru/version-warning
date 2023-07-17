<?php

namespace audunru\VersionWarning\Services;

use audunru\VersionWarning\Contracts\VersionServiceContract;
use audunru\VersionWarning\Exceptions\VersionWarningException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class NodeVersionService extends BaseVersionService implements VersionServiceContract
{
    /**
     * Get app version running on the server.
     */
    public function getAppVersion(): string
    {
        try {
            $json = File::get($this->getPackageJsonPath());
            $packageJson = json_decode($json, associative: true, flags: JSON_THROW_ON_ERROR);

            return Arr::get($packageJson, 'version', '');
        } catch (FileNotFoundException $e) {
            throw new VersionWarningException(sprintf('Could not open file %s', $this->getPackageJsonPath()), previous: $e);
        } catch (\JsonException $e) {
            throw new VersionWarningException(sprintf('Could not decode JSON in %s', $this->getPackageJsonPath()), previous: $e);
        }
    }

    /**
     * Get app version running on the client.
     */
    public function getClientVersion(Request $request): string
    {
        return $request->header($this->getClientVersionHeaderName(), '');
    }

    /**
     * Get path to package.json containing the app version.
     */
    protected function getPackageJsonPath(): string
    {
        return base_path('package.json');
    }
}
