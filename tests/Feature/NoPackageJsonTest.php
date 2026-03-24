<?php

namespace audunru\VersionWarning\Tests\Feature;

use audunru\VersionWarning\Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Mockery;

class NoPackageJsonTest extends TestCase
{
    public function test_logs_error_when_package_json_is_missing()
    {
        Log::shouldReceive('error')
            ->once()
            ->with('Could not determine app version', Mockery::hasKey('exception'));

        $response = $this->get('/test', ['X-App-Version' => '1.2.3']);

        $response
            ->assertOk()
            ->assertHeaderMissing('X-App-Version')
            ->assertHeaderMissing('X-Version-Warning');
    }
}
