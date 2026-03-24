<?php

namespace audunru\VersionWarning\Tests\Feature;

use audunru\VersionWarning\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mockery;

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

    public function test_adds_version_warning_header_when_headers_do_not_match()
    {
        $response = $this->get('/test', ['X-App-Version' => '1.2.3']);

        $response
            ->assertOk()
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeader('X-Version-Warning', '1');
    }

    public function test_does_not_add_version_warning_header_when_versions_match()
    {
        $response = $this->get('/test', ['X-App-Version' => '3.2.1']);

        $response
            ->assertOk()
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeaderMissing('X-Version-Warning');
    }

    public function test_adds_app_version_header_when_client_version_header_is_missing()
    {
        $response = $this->get('/test');

        $response
            ->assertOk()
            ->assertHeader('X-App-Version', '3.2.1')
            ->assertHeaderMissing('X-Version-Warning');
    }

    public function test_cache_is_enabled_by_default()
    {
        $cacheRepository = Cache::driver();
        $cacheRepositorySpy = Mockery::spy($cacheRepository);
        Cache::swap($cacheRepositorySpy);

        $response = $this->get('/test');

        $response
            ->assertOk();

        $cacheRepositorySpy
            ->shouldHaveReceived('rememberForever')
            ->once();

        $this->assertEquals('3.2.1', Cache::get('app-version'));
    }
}
