<?php

namespace audunru\VersionWarning\Tests\Feature;

use audunru\VersionWarning\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class HeaderTest extends TestCase
{
    public function testClientVersionIsOlderThanServerVersion()
    {
        File::partialMock()
            ->shouldReceive('get')
            ->once()
            ->andReturn('{"version": "3.2.1"}');

        $response = $this->get('/test', ['X-App-Version' => '1.2.3']);

        $response
            ->assertStatus(200)
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeader('X-Version-Warning', '1');
    }

    public function testClientVersionIsSameAsServerVersion()
    {
        File::partialMock()
            ->shouldReceive('get')
            ->once()
            ->andReturn('{"version": "3.2.1"}');

        $response = $this->get('/test', ['X-App-Version' => '3.2.1']);

        $response
            ->assertStatus(200)
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeaderMissing('X-Version-Warning');
    }

    public function testClientVersionHeaderIsMissing()
    {
        File::partialMock()
            ->shouldReceive('get')
            ->once()
            ->andReturn('{"version": "3.2.1"}');

        $response = $this->get('/test');

        $response
            ->assertStatus(200)
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeaderMissing('X-Version-Warning');
    }

    public function testLogsErrorWhenPackageJsonIsMissing()
    {
        Log::shouldReceive('error')
            ->once()
            ->with('Could not determine app version', \Mockery::hasKey('exception'));

        $response = $this->get('/test', ['X-App-Version' => '1.2.3']);

        $response
            ->assertStatus(200)
            ->assertHeaderMissing('X-App-Version')
            ->assertHeaderMissing('X-Version-Warning');
    }

    public function testLogsErrorWhenPackageJsonIsInvalid()
    {
        File::partialMock()
            ->shouldReceive('get')
            ->once()
            ->andReturn('{"version" "3.2.1"}');

        Log::shouldReceive('error')
            ->once()
            ->with('Could not determine app version', \Mockery::hasKey('exception'));

        $response = $this->get('/test', ['X-App-Version' => '1.2.3']);

        $response
            ->assertStatus(200)
            ->assertHeaderMissing('X-App-Version')
            ->assertHeaderMissing('X-Version-Warning');
    }
}
