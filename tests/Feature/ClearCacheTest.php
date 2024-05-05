<?php

namespace audunru\VersionWarning\Tests\Feature;

use audunru\VersionWarning\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class ClearCacheTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        File::partialMock()
            ->shouldReceive('get')
            ->once()
            ->andReturn('{"version": "3.2.1"}');
    }

    public function testCacheCanBeCleared()
    {
        $this->assertNull(Cache::get('app-version'));

        $this->get('/test');

        $this->assertEquals('3.2.1', Cache::get('app-version'));

        $this->artisan('version-warning:clear')
            ->expectsOutput('Cleared version-warning cache')
            ->assertSuccessful();

        $this->assertNull(Cache::get('app-version'));
    }

    public function testCacheIsAutomaticallyClearedOnEvent()
    {
        $this->assertNull(Cache::get('app-version'));

        $this->get('/test');

        $this->assertEquals('3.2.1', Cache::get('app-version'));

        Event::dispatch('some-event');

        $this->assertNull(Cache::get('app-version'));
    }

    public function testCacheIsNotAutomaticallyClearedOnOtherEvent()
    {
        $this->assertNull(Cache::get('app-version'));

        $this->get('/test');

        $this->assertEquals('3.2.1', Cache::get('app-version'));

        Event::dispatch('other:event');

        $this->assertEquals('3.2.1', Cache::get('app-version'));
    }
}
