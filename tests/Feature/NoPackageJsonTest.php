<?php

namespace audunru\VersionWarning\Tests\Feature;

use audunru\VersionWarning\Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Mockery;

class NoPackageJsonTest extends TestCase
{
    public function testLogsErrorWhenPackageJsonIsMissing()
    {
        Log::shouldReceive('error')
          ->once()
          ->with('Could not determine app version', Mockery::hasKey('exception'));

        $response = $this->get('/test', ['X-App-Version' => '1.2.3']);

        $response
          ->assertStatus(200)
          ->assertHeaderMissing('X-App-Version')
          ->assertHeaderMissing('X-Version-Warning');
    }
}
