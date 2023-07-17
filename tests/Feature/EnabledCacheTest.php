<?php

namespace audunru\VersionWarning\Tests\Feature;

use audunru\VersionWarning\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class EnabledCacheTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        File::partialMock()
            ->shouldReceive('get')
            ->once()
            ->andReturn('{"version": "3.2.1"}');
    }

    public function testAddsVersionWarningHeaderWhenHeadersDoNotMatch()
    {
        $response = $this->get('/test', ['X-App-Version' => '1.2.3']);

        $response
            ->assertStatus(200)
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeader('X-Version-Warning', '1');
    }

    public function testDoesNotAddVersionWarningHeaderWhenVersionsMatch()
    {
        $response = $this->get('/test', ['X-App-Version' => '3.2.1']);

        $response
            ->assertStatus(200)
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeaderMissing('X-Version-Warning');
    }

    public function testAddsAppVersionHeaderWhenClientVersionHeaderIsMissing()
    {
        $response = $this->get('/test');

        $response
            ->assertStatus(200)
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeaderMissing('X-Version-Warning');
    }

    public function testCacheIsEnabledByDefault()
    {
        $cacheRepository = Cache::driver();
        $cacheRepositorySpy = \Mockery::spy($cacheRepository);
        Cache::swap($cacheRepositorySpy);

        $response = $this->get('/test');

        $response
            ->assertStatus(200);

        $cacheRepositorySpy
            ->shouldHaveReceived('rememberForever')
            ->once();

        $this->assertEquals('3.2.1', Cache::get('app-version'));
    }
}
