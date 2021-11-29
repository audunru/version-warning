<?php

namespace audunru\VersionWarning\Tests\Feature;

use audunru\VersionWarning\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mockery;

class DisabledCacheTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('VERSION_WARNING_CACHE_ENABLED=false');

        parent::setUp();

        File::partialMock()
            ->shouldReceive('get')
            ->once()
            ->andReturn('{"version": "3.2.1"}');
    }

    protected function tearDown(): void
    {
        putenv('VERSION_WARNING_CACHE_ENABLED');
        parent::tearDown();
    }

    public function testCacheCanBeDisabled()
    {
        $cacheRepository = Cache::driver();
        $cacheRepositorySpy = Mockery::spy($cacheRepository);
        Cache::swap($cacheRepositorySpy);

        $cacheRepositorySpy
            ->shouldReceive('rememberForever')
            ->never();

        $response = $this->get('/test');

        $response
            ->assertStatus(200)
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeaderMissing('X-Version-Warning');

        $this->assertEquals('', Cache::get(config('version-warning.cache.key')));
    }
}
